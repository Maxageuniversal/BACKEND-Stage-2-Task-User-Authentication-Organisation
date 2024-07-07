#!/bin/bash

# Function to add Swagger annotations to a Laravel controller
add_swagger_annotations() {
  local file_path=$1

  # Check if the file exists
  if [[ ! -f "$file_path" ]]; then
    echo "File $file_path does not exist."
    return 1
  fi

  # Add Swagger annotations to the file
  cat << 'EOF' > "$file_path"
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
EOF

  echo "Swagger annotations added to $file_path."
}

# Path to the controller file
controller_path="app/Http/Controllers/ExampleController.php"

# Add Swagger annotations to the specified controller
add_swagger_annotations "$controller_path"
