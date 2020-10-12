<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 * version="1.0.0",
 * title="Znaidi Mahdi Starter - Open API",
 * description="APIs documentation usin OpenAPI",
 *
 *
 * @OA\Contact(
 *   name="Znaidi Mahdi",
 *   email="mahdi.znaidi@esprit.tn"
 *   )
 * )
 *
 * @OA\Server(
 *   url="http://localhost:8000/api",
 *   description="a local dev server"
 * )
 *
 */

/**
 * @OA\SecurityScheme(
 *     type="http",
 *     name="Authorization",
 *     in="header",
 *     securityScheme="Password Based",
 *     bearerFormat="JWT",
 *     scheme="bearer"
 * )
 */
