<?php

namespace App\State;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Factory\QuestionGroupFactory;
use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProcessorInterface<Quiz, Quiz|void>
 */
final class QuizProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $persistProcessor,
        private ProcessorInterface $removeProcessor,
        private QuestionGroupFactory $questionGroupFactory,
        private EntityManagerInterface $entityManager,
        private Security $security,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($operation instanceof DeleteOperationInterface) {
            return $this->removeProcessor->process($data, $operation, $uriVariables, $context);
        }

        assert($data instanceof Quiz);

        // Set owner
        if ($data->getCreatedBy() === null && $this->security->getUser()) {
            $data->setCreatedBy($this->security->getUser());
        }

        // Create default question group
        if (count($data->getQuestionGroups()) === 0) {
            $group = $this->questionGroupFactory->createWithQuiz($data);
            $this->entityManager->persist($group);
            $this->entityManager->persist($data);
        }

        // Set is grouped true if has children
        if (count($data->getChildrens()) > 0) {
            $data->setIsGrouped(true);
        } else {
            $data->setIsGrouped(false);
        }

        // Set is grouped true if parent has children
        if ($data->getParent() instanceof Quiz) {
            $parent = $data->getParent();
            $parent->setIsGrouped(true);
        }

        // Calculate the level
        $level = $this->calculateLevel($data, 1);
        $data->setLevel($level);

        // Set updatedAt
        $data->setUpdatedAt(new \DateTime());

        $result = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        return $result;
    }

    public function calculateLevel(Quiz $quiz, $currentLevel = 1): int
    {
        if ($quiz->getParent() instanceof Quiz) {
            // If the quiz has a parent, recursively calculate the level
            return $this->calculateLevel($quiz->getParent(), $currentLevel + 1);
        } else {
            // Base case: the quiz has no parent, return the current level
            return $currentLevel;
        }
    }
}
