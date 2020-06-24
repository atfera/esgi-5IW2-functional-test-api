<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\OffersApplicationsController;
use App\Controller\GetOwnedOffersController;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource(
 *     normalizationContext={"groups"={"offers_get"}},
 *     collectionOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_ADMIN')"
 *          },
 *          "post"={
 *              "security"="is_granted('ROLE_RECRUITER')",
 *          },
 *          "get_my_offer"={
 *              "method"="GET",
 *              "path"="/offers/owned",
 *              "controller"=GetOwnedOffersController::class,
 *              "defaults"={"_api_receive"=false}
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_RECRUITER') and object.getRecruiter().getId() == user.getId()"
 *          },
 *          "patch"={
 *              "security"="is_granted('ROLE_RECRUITER') and object.getRecruiter().getId() == user.getId()"
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_RECRUITER') and object.getRecruiter().getId() == user.getId()"
 *          },
 *          "delete"={
 *              "security"="is_granted('ROLE_RECRUITER') and object.getRecruiter().getId() == user.getId()"
 *          },
 *          "offers_applications"={
 *              "method"="PATCH",
 *              "path"="/offers/{id}/applicants",
 *              "controller"=OffersApplicationsController::class,
 *              "defaults"={"_api_receive"=false},
 *              "openapi_context"= {
 *                  "parameters"= {
 *                      {
 *                          "name": "id",
 *                          "in": "path",
 *                          "type": "string",
 *                          "required": true
 *                      },
 *                      {
 *                          "name": "applicants",
 *                          "in": "body",
 *                          "type": "array",
 *                          "required": true
 *                      },
 *                  }
 *              }
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 */
class Offer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"offers_get"})
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"applications_get","offers_get"})
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Groups({"applications_get","offers_get"})
     */
    private $descriptionCompany;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"applications_get","offers_get"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"applications_get","offers_get"})
     */
    private $typeContract;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"applications_get","offers_get"})
     */
    private $workplace;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Application", mappedBy="offer")
     * @Groups({"offers_get"})
     */
    private $applications;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="offers")
     * @Groups({"offers_get"})
     */
    private $recruiter;


    public function __construct()
    {
        $this->applications = new ArrayCollection();
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

    public function getDescriptionCompany(): ?string
    {
        return $this->descriptionCompany;
    }

    public function setDescriptionCompany(string $descriptionCompany): self
    {
        $this->descriptionCompany = $descriptionCompany;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTypeContract(): ?string
    {
        return $this->typeContract;
    }

    public function setTypeContract(string $typeContract): self
    {
        $this->typeContract = $typeContract;

        return $this;
    }

    public function getWorkplace(): ?string
    {
        return $this->workplace;
    }

    public function setWorkplace(string $workplace): self
    {
        $this->workplace = $workplace;

        return $this;
    }

    /**
     * @return Collection|Application[]
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): self
    {
        if (!$this->applications->contains($application)) {
            $this->applications[] = $application;
            $application->setOffer($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): self
    {
        if ($this->applications->contains($application)) {
            $this->applications->removeElement($application);
            // set the owning side to null (unless already changed)
            if ($application->getOffer() === $this) {
                $application->setOffer(null);
            }
        }

        return $this;
    }

    public function getRecruiter(): ?User
    {
        return $this->recruiter;
    }

    public function setRecruiter(?User $recruiter): self
    {
        $this->recruiter = $recruiter;

        return $this;
    }
}
