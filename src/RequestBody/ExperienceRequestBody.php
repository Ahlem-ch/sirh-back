<?php


namespace App\RequestBody;


/**
 * Class Experience
 *
 * @package Experience
 *
 * @author  Mahdi Znaidi<Mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="Experience model",
 *     title="ExperienceRequestBody",
 *     @OA\Xml(
 *         name="Experience"
 *     )
 * )
 */
class ExperienceRequestBody
{


    /**
     * @OA\Property(
     *     format="string",
     *     description="Experience intitule",
     *     title="intitule",
     * )
     *
     * @var string
     */
    private $intitule;


    /**
     * @OA\Property(
     *     format="string",
     *     description="Experience date_debut",
     *     title="date_debut",example="2019-10-14T14:43:45+00:00"
     * )
     *
     * @var string
     */
    private $date_debut;

    /**
     * @OA\Property(
     *     format="string",
     *     description="Experience date_fin",
     *     title="date_fin",example="2019-10-14T14:43:45+00:00"
     * )
     *
     * @var string
     */
    private $date_fin;





}
