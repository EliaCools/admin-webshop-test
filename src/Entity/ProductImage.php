<?php

namespace App\Entity;

use App\Repository\ProductImageRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\Entity(repositoryClass=ProductImageRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class ProductImage
{

    public const RELATIVE_PRODUCT_PATH = 'products/';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="images", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    private $file;

    /**
     * @ORM\Column(type="datetime", nullable= true)
     */
    private $updated;

    /**
     * @ORM\ManyToMany(targetEntity=ProductVariation::class, inversedBy="productImages")
     */
    private $productVariations;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $relativePathFromServerImageFolder = self::RELATIVE_PRODUCT_PATH;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $fileExtension;

    public function __construct()
    {
        $this->productVariations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function setFile(UploadedFile $file = null): void
    {
        $this->file = $file;
    }


    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // we use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and target filename as params
        $this->getFile()->move(
            $_ENV['SERVER_IMAGE_FOLDER'] . self::RELATIVE_PRODUCT_PATH,
            $this->getFile()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        $this->filename = $this->getFile()->getClientOriginalName();
        $this->relativePathFromServerImageFolder = self::RELATIVE_PRODUCT_PATH;
        $this->setFileExtension(pathinfo($this->getFile()->getClientOriginalName())['extension']);

        // clean up the file property as you won't need it anymore
        $this->setFile(null);
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function lifecycleFileUpload()
    {
        $this->upload();
    }
    

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = new datetime('now');


        return $this;
    }

    public function refreshUpdated()
    {
        $this->setUpdated(new \DateTime());
    }

    public function __tostring()
    {
        return $this->getFilename();
    }

    /**
     * @return Collection|ProductVariation[]
     */
    public function getProductVariations(): Collection
    {
        return $this->productVariations;
    }

    public function addProductVariation(ProductVariation $productVariation): self
    {
        if (!$this->productVariations->contains($productVariation)) {
            $this->productVariations[] = $productVariation;
        }

        return $this;
    }

    public function removeProductVariation(ProductVariation $productVariation): self
    {
        $this->productVariations->removeElement($productVariation);

        return $this;
    }

    public function getRelativePathFromServerImageFolder(): ?string
    {
        return $this->relativePathFromServerImageFolder;
    }

    public function setRelativePathFromServerImageFolder(string $relativePathFromServerImageFolder): self
    {
        $this->relativePathFromServerImageFolder = $relativePathFromServerImageFolder;

        return $this;
    }

    public function getFileExtension(): ?string
    {
        return $this->fileExtension;
    }

    public function setFileExtension(string $fileExtension): self
    {
        $this->fileExtension = $fileExtension;

        return $this;
    }
}
