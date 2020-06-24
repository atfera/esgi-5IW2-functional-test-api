<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\ApplicationsValidationController;
use App\Controller\GetOwnedApplicationsController;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ApiResource(
 *      normalizationContext={"groups"={"applications_get"}},
 *      collectionOperations={
 *          "get",
 *          "post"={
 *              "security"="is_granted('ROLE_APPLICANT')"
 *          },
 *          "offers_applications"={
 *              "method"="GET",
 *              "path"="/applications/validate/{token}",
 *              "controller"=ApplicationsValidationController::class,
 *              "defaults"={"_api_receive"=false},
 *              "openapi_context"= {
 *                  "parameters"= {
 *                      {
 *                          "name": "token",
 *                          "in": "path",
 *                          "type": "string",
 *                          "required": true
 *                      },
 *                  }
 *              }
 *          },
 *          "get_my_offer"={
 *              "method"="GET",
 *              "path"="/applications/owned",
 *              "controller"=GetOwnedApplicationsController::class,
 *              "defaults"={"_api_receive"=false}
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_RECRUITER') or (is_granted('ROLE_APPLICANT') and object.getApplicant().getId() == user.getId())"
 *          },
 *          "patch"={
 *              "security"="is_granted('ROLE_APPLICANT') and object.getApplicant().getId() == user.getId()"
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_APPLICANT') and object.getApplicant().getId() == user.getId()"
 *          },
 *          "delete"={
 *              "security"="is_granted('ROLE_APPLICANT') and object.getApplicant().getId() == user.getId()"
 *          }
 *     }
 * )
 * @ApiFilter(RangeFilter::class, properties={"salaryClaim"})
 * @ApiFilter(SearchFilter::class, properties={"status.name": "exact"})
 * @ORM\Entity(repositoryClass="App\Repository\ApplicationRepository")
 */
class Application
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"applications_get"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true))
     * @Groups({"applications_get"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true))
     * @Groups({"applications_get"})
     */
    private $gender;

    /**
     * @var MediaObject|null
     * @ORM\ManyToOne(targetEntity=MediaObject::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true))
     * @Groups({"applications_get"})
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true))
     * @Groups({"applications_get"})
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=255, nullable=true))
     * @Groups({"applications_get"})
     */
    private $address;

    /**
     * @ORM\Column(type="text", nullable=true))
     * @Groups({"applications_get"})
     */
    private $motivationField;

    /**
     * @ORM\Column(type="float", nullable=true))
     * @Groups({"applications_get"})
     */
    private $salaryClaim;

    /**
     * @var MediaObject|null
     * @ORM\ManyToOne(targetEntity=MediaObject::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $cv;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Offer", inversedBy="applications")
     * @Groups({"applications_get"})
     */
    private $offer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="applications")
     * @Groups({"applications_get"})
     */
    private $applicant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="applications")
     */
    private $status;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $token;

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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(string $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getMotivationField(): ?string
    {
        return $this->motivationField;
    }

    public function setMotivationField(string $motivationField): self
    {
        $this->motivationField = $motivationField;

        return $this;
    }

    public function getSalaryClaim(): ?float
    {
        return $this->salaryClaim;
    }

    public function setSalaryClaim(float $salaryClaim): self
    {
        $this->salaryClaim = $salaryClaim;

        return $this;
    }

    /**
     * @return MediaObject|null
     */
    public function getCv(): ?MediaObject
    {
        return $this->cv;
    }

    /**
     * @param MediaObject|null $cv
     */
    public function setCv(?MediaObject $cv): void
    {
        $this->cv = $cv;
    }


    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }

    public function getApplicant(): ?User
    {
        return $this->applicant;
    }

    public function setApplicant(?User $applicant): self
    {
        $this->applicant = $applicant;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }
}
