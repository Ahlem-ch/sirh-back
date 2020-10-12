<?php


namespace App\RequestBody;


/**
 * Class Diplome
 *
 * @package Diplome
 *
 * @author  Mahdi Znaidi<Mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="Diplome model",
 *     title="DiplomeRequestBody",
 *     @OA\Xml(
 *         name="Diplome"
 *     )
 * )
 */
class DiplomeRequestBody
{


    /**
     * @OA\Property(
     *     format="string",
     *     description="Diplome libelle",
     *     title="libelle",
     * )
     *
     * @var string
     */
    private $libelle;

    /**
     * @OA\Property(
     *     format="string",
     *     description="Diplome type",
     *     title="type",
     * )
     *
     * @var string
     */
    private $type;

    /**
     * @OA\Property(
     *     format="string",
     *     description="Departement type",
     *     title="annee",example="2019-10-09T00:00:00+00:00"
     * )
     *
     * @var string
     */
    private $annee;


    /**
     * @OA\Property(
     *     format="string",
     *     description="Diplome ecole",
     *     title="ecole",
     * )
     *
     * @var string
     */
    private $ecole;





}
