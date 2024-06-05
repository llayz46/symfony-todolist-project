<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $icon = null;

    /**
     * @var Collection<int, Todolist>
     */
    #[ORM\OneToMany(targetEntity: Todolist::class, mappedBy: 'category', orphanRemoval: true)]
    private Collection $todolists;

    public function __construct()
    {
        $this->todolists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return Collection<int, Todolist>
     */
    public function getTodolists(): Collection
    {
        return $this->todolists;
    }

    public function addTodolist(Todolist $todolist): static
    {
        if (!$this->todolists->contains($todolist)) {
            $this->todolists->add($todolist);
            $todolist->setCategory($this);
        }

        return $this;
    }

    public function removeTodolist(Todolist $todolist): static
    {
        if ($this->todolists->removeElement($todolist)) {
            // set the owning side to null (unless already changed)
            if ($todolist->getCategory() === $this) {
                $todolist->setCategory(null);
            }
        }

        return $this;
    }
}
