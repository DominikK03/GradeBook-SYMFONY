<?php

namespace App\MessageHandler;

use App\Entity\Grade;
use App\Entity\Homework;
use App\Entity\Student;
use App\Entity\StudentParent;
use App\Message\NotifyStudentsMessage;
use App\Service\HomeworkService;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsMessageHandler]
readonly class NotifyStudentHandler
{
    public function __construct(private HomeworkService $homeworkService,
                                private TranslatorInterface $translator,
                                private MailerInterface $mailer)
    {
    }

    public function __invoke(NotifyStudentsMessage $message)
    {
        $homework = $this->homeworkService->getHomeworkById($message->homeworkID);
        if (!$homework){
            return;
        }
        $students = $homework->getGroup()->getStudents();
        foreach ($students as $student){
            $this->sendNotificationEmail($homework, $student);
        }
    }

    private function sendNotificationEmail(Homework $homework, Student $student): void
    {
        $subjectName = $this->translator->trans($homework->getSubject()->value, [], 'subjects', 'pl');
        $email = (new Email())
            ->from('noreply@demomailtrap.co')
            ->to($student->getUser()->getEmail())
            ->subject(sprintf("Nowe zadanie z przedmiotu %s!", $subjectName))
            ->text(sprintf(
                "Witaj! \n\n" .
                "Zostało załączone nowe zadanie z przedmiotu %s, \n\n" .
                "Temat: %s\n" .
                "Opis: %s\n" .
                "Data zakończenia: %s\n" .
                "Nauczyciel: %s\n",
                $subjectName,
                $homework->getTopic(),
                $homework->getDescription(),
                $homework->getDueDate()->format("d-m-Y"),
                $homework->getTeacher()->getPerson()->getFullName()
            ));
        $this->mailer->send($email);

    }

}
