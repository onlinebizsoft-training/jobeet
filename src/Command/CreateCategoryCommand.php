<?php


namespace App\Command;

use App\Service\CategoryService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateCategoryCommand extends Command
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * CreateCategoryCommand constructor.
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
        parent::__construct();
    }

    /**
     * Fill name of category
     */
    protected function configure()
    {
        $this->setName('app:create-category')
             ->setDescription('Creates a new category.')
             ->setHelp('This command allows you to add new category in database.')
             ->addArgument('name', InputArgument::REQUIRED, 'The name of the category.');
    }

    /**
     * This method is second chance if user forget to fill the name
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('name')) {
            $question = new Question('<question>Please choose a name:</question> ');
            $question->setValidator(function ($name) {
                if (empty($name)) {
                    throw new \Exception('Name can not be empty.');
                }
                return $name;
            });
            // Return the name which user fill
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument('name', $answer);
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Category Creator',
            '================',
            ''
        ]);
        $this->categoryService->create($input->getArgument('name'));
        $output->writeln('<fg=green>Category successfully created!');
    }
}