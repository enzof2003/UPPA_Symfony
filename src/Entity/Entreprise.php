<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EntrepriseRepository::class)
 */
class Entreprise
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="L’activité doit être renseignée") 
     */
    private $activite;

    /**
     * @Assert\Regex(pattern="#rue|avenue|boulevard|impasse|allée|place|route|voie#", message="Le type de route/voie semble incorrect")
     * @Assert\Regex(pattern="# [0-9]{5} #", message="Il semble y avoir un problème avec le code postal ")
     * @Assert\Regex(pattern="#[1-9][0-9]{0,2} #", message="Le numéro de rue semble incorrect ")
     * @ORM\Column(type="string", length=250)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=75)
     * @Assert\Length(
     * min = 4,
     * minMessage = "Le nom de l’entreprise doit au minimum être long de {{ limit }} caractères")     
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\Url(message="Cette valeur n'est pas une URL valide")
     */
    private $URLSite;

    /**
     * @ORM\OneToMany(targetEntity=Stage::class, mappedBy="entreprise", orphanRemoval=true)
     */
    private $stages;

    public function __construct()
    {
        $this->stages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivite(): ?string
    {
        return $this->activite;
    }

    public function setActivite(string $activite): self
    {
        $this->activite = $activite;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getURLSite(): ?string
    {
        return $this->URLSite;
    }

    public function setURLSite(?string $URLSite): self
    {
        $this->URLSite = $URLSite;

        return $this;
    }

    /**
     * @return Collection|Stage[]
     */
    public function getStages(): Collection
    {
        return $this->stages;
    }

    public function addStage(Stage $stage): self
    {
        if (!$this->stages->contains($stage)) {
            $this->stages[] = $stage;
            $stage->setEntreprise($this);
        }

        return $this;
    }

    public function removeStage(Stage $stage): self
    {
        if ($this->stages->removeElement($stage)) {
            // set the owning side to null (unless already changed)
            if ($stage->getEntreprise() === $this) {
                $stage->setEntreprise(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getNom();
    }
}
