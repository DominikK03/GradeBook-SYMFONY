<?php

namespace App\MessageHandler;

use App\Entity\Grade;
use App\Entity\Student;
use App\Entity\StudentParent;
use App\Message\NotifyParentMessage;
use App\Service\GradeService;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsMessageHandler]
readonly class NotifyParentMessageHandler
{
    public function __construct(private GradeService        $gradeService,
                                private MailerInterface     $mailer,
                                private TranslatorInterface $translator)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(NotifyParentMessage $message): void
    {

        $grade = $this->gradeService->getGradeById($message->gradeId);
        if (!$grade) {
            return;
        }

        $student = $grade->getStudent();
        $parent = $student->getStudentParent();
        if (!$parent?->getContactEmail()) {
            return;
        }
        $this->sendNotificationEmail($grade, $parent, $student);
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function sendNotificationEmail(Grade $grade, StudentParent $parent, Student $student): void
    {
        $subjectName = $this->translator->trans($grade->getSubject()->value, [], 'subjects', 'pl');
        $gradeTypeName = $this->translator->trans($grade->getType()->value, [], 'grade_types', 'pl');
        $email = (new Email())
            ->from('noreply@demomailtrap.co')
            ->to($parent->getContactEmail())
            ->subject(sprintf("%s %s otrzymał/a nową ocenę!", $student->getPerson()->getFirstName(), $student->getPerson()->getLastName()))
            ->text(sprintf(
                "Witaj! \n\n" .
                "Twoje dziecko %s otrzymało nową ocenę z przedmiotu %s \n\n" .
                "Przedmiot: %s\n" .
                "Ocena: %d\n" .
                "Typ: %s\n" .
                "Waga: %s\n" .
                "Nauczyciel: %s\n" .
                "Data: %s\n" .
                "Opis: %s\n",
                $student->getPerson()->getFullName(),
                $subjectName,
                $subjectName,
                $grade->getValue(),
                $gradeTypeName,
                $grade->getWage(),
                $grade->getTeacher()->getPerson()->getFullName(),
                $grade->getDate()->format('d.m.Y H:i'),
                $grade->getDescription() ?: 'Brak opisu'
            ));
        $this->mailer->send($email);

    }

}
