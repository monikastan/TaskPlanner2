<?php

namespace TaskBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class SendRemindersCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('Task:send-reminders');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln([
            'Reminders Creator',
            '=================',
            '',
        ]);

        $tasksDelay = $this->getContainer()->get('doctrine')->getRepository("TaskBundle:Task")->findDelayTask();

        foreach ($tasksDelay as $task) {
            
            $email = $task->getUser()->getEmail();
            $taskName = $task->getName();
            $userName = $task->getUser()->getUsername();
            $dateDelay = $task->getDeadline()->format('Y-m-d');
            
            $message = \Swift_Message::newInstance()
                    ->setSubject('Delayed Tasks Reminder')
                    ->setFrom('mon.serafinska@gmail.com')
                    ->setTo($email)
                    ->setBody("Witaj $userName! Masz przeterminowane zadanie: $taskName. Zadanie zostało przeterminowane dnia $dateDelay");
            
            $this->getContainer()->get('mailer')->send($message);
        }
    }

}
