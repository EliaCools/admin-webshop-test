<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=ProductImage::class, mappedBy="product", orphanRemoval=true, cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity=ProductVariation::class, mappedBy="product", orphanRemoval=true, cascade={"persist"})
     */
    private $productVariations;


    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->productVariations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|ProductImage[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ProductImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProduct($this);
        }

        return $this;
    }

    public function removeImage(ProductImage $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductVariation[]
     */
    public function getProductVariations(): Collection
    {
        return $this->getProductVariationsWithoutBase();
    }

    /**
     * @return Collection|ProductVariation[]
     */
    public function getProductVariationsWithoutBase(): Collection
    {
        $varsWithoutBase = new ArrayCollection();
        foreach ($this->productVariations as $variation) {
            if ($variation->getIsBaseProduct() === false) {
                $varsWithoutBase[] = $variation;
            }
        }
        return $varsWithoutBase;

    }

    public function addProductVariation(ProductVariation $productVariation): self
    {
        if (!$this->productVariations->contains($productVariation)) {
            $this->productVariations[] = $productVariation;
            $productVariation->setProduct($this);
        }

        return $this;
    }

    public function removeProductVariation(ProductVariation $productVariation): self
    {
        if ($this->productVariations->removeElement($productVariation)) {
            // set the owning side to null (unless already changed)
            if ($productVariation->getProduct() === $this) {
                $productVariation->setProduct(null);
            }
        }

        return $this;
    }

    public function setBaseProduct(ProductVariation $productVariation): self
    {
        $this->addProductVariation($productVariation);
        $productVariation->setProduct($this);
        $productVariation->setIsBaseProduct(true);

        return $this;


        /* $this->baseProduct = $productVariation;
         return $this;*/
    }

    public function getBaseProduct(): ?ProductVariation
    {
        // Normally it always should be the first in the collection, but a foreach loop is added just in case
        if (count($this->productVariations) !== 0) {
            if ($this->productVariations[0]->getIsBaseProduct() === true) {
                return $this->productVariations[0];
            }
            foreach ($this->productVariations as $variation) {
                if ($variation->getIsBaseProduct() === true) {
                    return $variation;
                }
            }
        }
        return null;
    }

    public function __tostring(): string
    {
        return $this->getName();
    }
}
