<?php


namespace App\RequestBody;


/**
 * Class TypeExp
 *
 * @package TypeExp
 *
 * @author  Mahdi Znaidi<Mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="TypeExp model",
 *     title="TypeExpRequestBody",
 *     @OA\Xml(
 *         name="TypeExp"
 *     )
 * )
 */
class TypeExpRequestBody
{

    /**
     * @OA\Property(
     *     format="string",
     *     description="TypeExp libelle",
     *     title="libelle",
     * )
     *
     * @var string
     */
    private $libelle;


}
