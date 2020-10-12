<?php


namespace App\RequestBody;

use App\Entity\Timestamps;

/**
 * Class User
 *
 * @package User
 *
 * @author  Mahdi Znaidi<Mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="User model",
 *     title="UserRequestBody",
 *     @OA\Xml(
 *         name="User"
 *     )
 * )
 */
class UserRequestBody
{
    use Timestamps;


    /**
     * @OA\Property(
     *     format="string",
     *     description="User email",
     *     title="email",example="admin@admin.com"
     * )
     * @var string
     */

    private $email;

    /**
     * @var string The hashed password
     * @OA\Property(
     *     format="string",
     *     description="User password",
     *     title="password"
     * )
     * @var string
     */
    private $password;

    /**
     * @OA\Property(
     *     format="string",
     *     description="User matricule_hr",
     *     title="matricule_hr",
     * )
     *
     * @var string
     */
    private $matricule_hr;

    /**
     * @OA\Property(
     *     format="string",
     *     description="User nom",
     *     title="nom",
     * )
     * @var string
     */
    private $nom;

    /**
     * @OA\Property(
     *     format="string",
     *     description="User prenom",
     *     title="prenom",
     * )
     * @var string
     */
    private $prenom;


    /**
     * @OA\Property(
     *     format="string",
     *     description="User adresse",
     *     title="adresse",
     * )
     * @var string
     */
    private $adresse;

    /**
     * @OA\Property(
     *     format="string",
     *     description="User numéro_telephone",
     *     title="numéro_téléphone",
     * )
     * @var string
     */
    private $num_telephone;

    /**
     * @OA\Property(
     *     format="string",
     *     description="User cin_passport",
     *     title="cin_passport",
     * )
     * @var string
     */
    private $cin_passport;

    /**
     * @OA\Property(
     *     format="string",
     *     description="User etat_civil",
     *     title="etat_civil",
     * )
     * @var string
     */
    private $etat_civil;


    /**
     * @OA\Property(
     *     format="string",
     *     description="User image",
     *     title="image",
     * )
     * @var string
     */
    private $image;

    /**
     * @OA\Property(
     *     format="string",
     *     description="User date_naissance",
     *     title="date_naissance",example="2019-10-02T14:01:17+00:00"
     * )
     *
     * @var string
     */

    private $date_naissance;


    public function getUsername(): string
    {
        return (string) $this->email;
    }

}
