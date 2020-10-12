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
class CategorieRequestBody
{

    /**
     * @OA\Property(
     *     format="string",
     *     description="Categorie libelle",
     *     title="string",
     * )
     *
     * @var string
     */
    private $libelle;

    /**
     * @OA\Property(
     *     format="int",
     *     description="contrat id",
     *     title="string",
     * )
     *
     * @var integer
     */
    private $contrats;

}
