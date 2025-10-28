<?php

namespace App\Entity;

class BookSearch
{
    private ?string $bookTitle = null;

    public function getBookTitle(): ?string
    {
        return $this->bookTitle;
    }

    public function setBookTitle(?string $bookTitle): static
    {
        $this->bookTitle = $bookTitle;

        return $this;
    }
}