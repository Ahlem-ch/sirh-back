<?php


namespace App\RequestBody;


/**
 * Class Salaire
 *
 * @package Salaire
 *
 * @author  Mahdi Znaidi<Mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="Salaire model",
 *     title="SalaireRequestBody",
 *     @OA\Xml(
 *         name="Salaire"
 *     )
 * )
 */
class SalaireRequestBody
{

    /**
     * @OA\Property(
     *     format="float",
     *     description="Salaire brut",
     *     title="salaire_brut",
     * )
     *
     * @var float
     */
    private $salaire_brut;

    /**
     * @OA\Property(
     *     format="float",
     *     description="Salaire net",
     *     title="salaire_net",
     * )
     *
     * @var float
     */
    private $salaire_net;

    /**
     * @OA\Property(
     *     format="float",
     *     description="prime",
     *     title="prime",
     * )
     *
     * @var float
     */
    private $prime;

    /**
     * @OA\Property(
     *     format="string",
     *     description="Salaire date_debut",
     *     title="date_debut",example="2019-10-02T14:01:17+00:00"
     * )
     *
     * @var string
     */
    private $date_debut;

    /**
     * @OA\Property(
     *     format="string",
     *     description="Salaire date_fin",
     *     title="date_fin",example="2019-10-02T14:01:17+00:00"
     * )
     *
     * @var string
     */
    private $date_fin;

    /**
     * @OA\Property(
     *     format="int",
     *     description="Contrat id",
     *     title="contrat id"
     * )
     *
     * @var integer
     */
    private $contrat;
}
