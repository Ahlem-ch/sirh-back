<?php


namespace App\RequestBody;


/**
 * Class Contrat
 *
 * @package Contrat
 *
 * @author  Mahdi Znaidi<Mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="Contrat model",
 *     title="ContratRequestBody",
 *     @OA\Xml(
 *         name="Contrat"
 *     )
 * )
 */
class ContratRequestBody
{


    /**
     * @OA\Property(
     *     format="string",
     *     description="Contrat type",
     *     title="type",
     * )
     *
     * @var string
     */
    private $type;

    /**
     * @OA\Property(
     *     format="float",
     *     description="Contrat salaire actuel",
     *     title="salaire actuel",
     * )
     *
     * @var float
     */
    private $actuel_salaire;

    /**
     * @OA\Property(
     *     format="string",
     *     description="Contrat copie_contrat",
     *     title="copie_contrat",
     * )
     *
     * @var string
     */
    private $copie_contrat;

    /**
     * @OA\Property(
     *     format="int",
     *     description="Contrat user",
     *     title="user"
     * )
     *
     * @var integer
     */
    private $user;

}
