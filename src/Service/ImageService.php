<?php

declare(strict_types=1);

namespace App\Service;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\Answer;
use App\Entity\AnswerImage;
use App\Entity\Question;
use App\Entity\QuestionImage;
use App\Entity\Quiz;
use App\Entity\QuizImage;
use App\Entity\User;
use App\Entity\UserImage;
use App\Service\ImageUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ImageUploaderService $imageUploaderService
    ) {
    }

    public function addQuizImage(Quiz $quiz, UploadedFile $file, string $type): QuizImage
    {
        $path = $this->imageUploaderService->upload($file);

        $image = new QuizImage();
        $image->setType($type);
        $image->setPath($path);

        $quiz->addImage($image);
        $this->em->persist($image);
        $this->em->flush();

        return $image;
    }

    public function addQuestionImage(Question $question, UploadedFile $file, string $type): QuestionImage
    {
        $path = $this->imageUploaderService->upload($file);

        $image = new QuestionImage();
        $image->setType($type);
        $image->setPath($path);

        $question->addImage($image);
        $this->em->persist($image);
        $this->em->flush();

        return $image;
    }

    public function addAnswerImage(Answer $answer, UploadedFile $file, string $type): AnswerImage
    {
        $path = $this->imageUploaderService->upload($file);

        $image = new AnswerImage();
        $image->setType($type);
        $image->setPath($path);

        $answer->addImage($image);
        $this->em->persist($image);
        $this->em->flush();

        return $image;
    }

    public function addUserImage(User $user, UploadedFile $file, string $type): UserImage
    {
        $path = $this->imageUploaderService->upload($file);

        $image = new UserImage();
        $image->setType($type);
        $image->setPath($path);

        $user->addImage($image);
        $this->em->persist($image);
        $this->em->flush();

        return $image;
    }

    public function deleteQuizImage(QuizImage $image)
    {
        $isDeleted = $this->imageUploaderService->deleteImage($image->getPath());
        if ($isDeleted) {
            $this->em->remove($image);
            $this->em->flush();
        }
    }

    public function deleteQuestionImage(QuestionImage $image)
    {
        $isDeleted = $this->imageUploaderService->deleteImage($image->getPath());
        if ($isDeleted) {
            $this->em->remove($image);
            $this->em->flush();
        }
    }

    public function deleteAnswerImage(AnswerImage $image)
    {
        $isDeleted = $this->imageUploaderService->deleteImage($image->getPath());
        if ($isDeleted) {
            $this->em->remove($image);
            $this->em->flush();
        }
    }

    public function deleteUserImage(UserImage $image)
    {
        $isDeleted = $this->imageUploaderService->deleteImage($image->getPath());
        if ($isDeleted) {
            $this->em->remove($image);
            $this->em->flush();
        }
    }
}
