<?php


namespace App\Tests\Service;


use App\Entity\Job;
use App\Repository\JobRepository;
use App\Service\JobHistoryService;
use Doctrine\ORM\EntityManagerInterface;
use function PHPSTORM_META\expectedArguments;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class JobHistoryServiceTest extends TestCase
{
    public function testAddJob(): void
    {
        // Mock up an SessionInterface object
        $session = $this->getMockBuilder(SessionInterface::class)
                        ->getMock();

        // Mock up an EntityManagerInterface object
        $em = $this->getMockBuilder(EntityManagerInterface::class)
                   ->getMock();

        // Save jobs into session job_history
        $session->expects($this->once())
                ->method('set')
                ->with(
                    $this->equalTo('job_history'),
                    $this->callback(function ($jobs) {
                        return is_array($jobs)
                            && count($jobs) === 3
                            && array_shift($jobs) === 4;
                    })
                );

        // Get jobs
        $session->expects($this->once())
                ->method('get')
                ->with($this->equalTo('job_history'))
                ->willReturn([1, 2, 3]);

        // Mock up an Job object
        $job = $this->getMockBuilder(Job::class)
                    ->getMock();

        // Get id of job
        $job->expects($this->once())
            ->method('getId')
            ->willReturn(4);

        $history = new JobHistoryService($session, $em);
        $history->addJob($job);
    }

    public function testGetJobs(): void
    {
        // Mock up an JobRepository object
        $repository = $this->getMockBuilder(JobRepository::class)
                           ->disableOriginalConstructor()
                           ->getMock();

        // Find active job
        $repository->expects($this->exactly(3))
                   ->method('findActiveJob')
                   ->withConsecutive(
                       [$this->equalTo(1)],
                       [$this->equalTo(2)],
                       [$this->equalTo(3)]
                   )
                   ->will(
                       $this->onConsecutiveCalls(
                           (new Job())->setDescription('_1_'),
                           (new Job())->setDescription('_2_'),
                           (new Job())->setDescription('_3_')
                       )
                   );

        // Mock up an SessionInterface object
        $session = $this->getMockBuilder(SessionInterface::class)
                        ->getMock();

        // Get jobs from session job_history
        $session->expects($this->once())
                ->method('get')
                ->with($this->equalTo('job_history'))
                ->willReturn([1, 2, 3]);

        // Mock up an EntityManagerInterface object
        $em = $this->getMockBuilder(EntityManagerInterface::class)
                   ->getMock();

        // Get a repository of entity
        $em->expects($this->once())
           ->method('getRepository')
           ->willReturn($repository);

        $history = new JobHistoryService($session, $em);
        $jobs = $history->getJobs();

        $this->assertCount(3, $jobs);
        $this->assertEquals('_1_', $jobs[0]->getDescription());
        $this->assertEquals('_2_', $jobs[1]->getDescription());
        $this->assertEquals('_3_', $jobs[2]->getDescription());
    }
}