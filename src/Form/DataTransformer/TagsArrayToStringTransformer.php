<?php

namespace App\Form\DataTransformer;

use App\Entity\Blog;
use App\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;

class TagsArrayToStringTransformer implements DataTransformerInterface
{
    private $entityManager;
    private $blog;

    public function __construct(EntityManagerInterface $entityManager, Blog $blog)
    {
        $this->entityManager = $entityManager;
        $this->blog = $blog;
    }

    public function transform($tags): string
    {
        return implode(',', $tags->toArray());
    }

    public function reverseTransform($tagsNames): ArrayCollection
    {
        $tags = new ArrayCollection();

        if (!$tagsNames) return $tags;

        $names = explode(',', $tagsNames);
        foreach ($names as $name) {
            $tag = $this->entityManager->getRepository(Tag::class)->fetchUserTagByName($name);

            if (!$tag) {
                $tag = new Tag();
                $tag->setName($name);
                $tag->setBlog($this->blog);
            }

            $tags->add($tag);
        }

        $this->blog->setTags($tags);
        $this->entityManager->flush();

        return $tags;
    }
}