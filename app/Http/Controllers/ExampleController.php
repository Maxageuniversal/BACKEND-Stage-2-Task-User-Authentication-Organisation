<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/example",
     *     operationId="getExample",
     *     tags={"Example"},
     *     summary="Get example data",
     *     description="Returns example data.",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="string", example="example data")
     *         )
     *     )
     * )
     */
    public function getExample()
    {
        return response()->json(['data' => 'example data']);
    }

    /**
     * @OA\Post(
     *     path="/api/example",
     *     operationId="createExample",
     *     tags={"Example"},
     *     summary="Create example data",
     *     description="Creates example data.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="string", example="example data")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Example created",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="data", type="string", example="example data")
     *         )
     *     )
     * )
     */
    public function createExample(Request $request)
    {
        // Code to create example data
    }
}
