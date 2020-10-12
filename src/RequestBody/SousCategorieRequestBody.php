<?php


namespace App\RequestBody;


/**
 * Class SousCategorie
 *
 * @package SousCategorie
 *
 * @author  Mahdi Znaidi<Mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="SousCategorie model",
 *     title="SousCategorieRequestBody",
 *     @OA\Xml(
 *         name="Contrat"
 *     )
 * )
 */
class SousCategorieRequestBody
{

    /**
     * @OA\Property(
     *     format="string",
     *     description="SousCategorie libelle",
     *     title="string",
     * )
     *
     * @var string
     */
    private $libelle;

    /**
     * @OA\Property(
     *     format="int",
     *     description="categories id",
     *     title="string",
     * )
     *
     * @var integer
     */
    private $categories;

}
