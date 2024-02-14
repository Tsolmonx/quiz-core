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
        $data->setUpdatedAt(new \DateTime());

        $result = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        return $result;
    }
}
