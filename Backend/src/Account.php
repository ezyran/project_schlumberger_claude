<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Account
 *
 * @ORM\Table(name="Account", indexes={@ORM\Index(name="client", columns={"client"})})
 * @ORM\Entity
 */
class Account
{
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=256, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="passwordHash", type="string", length=256, nullable=false)
     */
    private $passwordhash;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client", referencedColumnName="id")
     * })
     */
    private $client;


    /**
     * Set email.
     *
     * @param string $email
     *
     * @return Account
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set passwordhash.
     *
     * @param string $passwordhash
     *
     * @return Account
     */
    public function setPasswordhash($passwordhash)
    {
        $this->passwordhash = $passwordhash;

        return $this;
    }

    /**
     * Get passwordhash.
     *
     * @return string
     */
    public function getPasswordhash()
    {
        return $this->passwordhash;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set client.
     *
     * @param \Client|null $client
     *
     * @return Account
     */
    public function setClient(\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client.
     *
     * @return \Client|null
     */
    public function getClient()
    {
        return $this->client;
    }
}
