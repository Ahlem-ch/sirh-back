<?php


namespace App\RequestBody;


/**
 * Class Document
 *
 * @package Document
 *
 * @author  Mahdi Znaidi<Mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="Document model",
 *     title="DocumentRequestBody",
 *     @OA\Xml(
 *         name="Document"
 *     )
 * )
 */
class DocumentRequestBody
{

    /**
     * @OA\Property(
     *     format="string",
     *     description="Document libelle",
     *     title="libelle_document",
     * )
     *
     * @var string
     */
    private $libelle_document;

    /**
     * @OA\Property(
     *     format="string",
     *     description="Document type",
     *     title="type_Document",
     * )
     *
     * @var string
     */
    private $type;

    /**
     * @OA\Property(
     *     format="string",
     *     description="Document piece_jointe",
     *     title="piece_jointe",
     * )
     *
     * @var string
     */
    private $piece_jointe;





}
