<?php


namespace App\RequestBody;


/**
 * Class Poste
 *
 * @package Poste
 *
 * @author  Mahdi Znaidi<Mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="Poste model",
 *     title="PostRequestBody",
 *     @OA\Xml(
 *         name="Post"
 *     )
 * )
 */
class PosteRequestBody
{

    /**
     * @OA\Property(
     *     format="string",
     *     description="Poste libelle",
     *     title="libelle_poste",
     * )
     *
     * @var string
     */
    private $libelle_poste;


}
