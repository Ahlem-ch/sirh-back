<?php


namespace App\RequestBody;


/**
 * Class Departement
 *
 * @package Departement
 *
 * @author  Mahdi Znaidi<Mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="Departement model",
 *     title="DepartementRequestBody",
 *     @OA\Xml(
 *         name="Departement"
 *     )
 * )
 */
class DepartementRequestBody
{

    /**
     * @OA\Property(
     *     format="string",
     *     description="Departement libelle",
     *     title="libelle_departement",
     * )
     *
     * @var string
     */
    private $libelle_departement;


}
