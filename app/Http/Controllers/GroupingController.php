<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GroupingController extends Controller
{
    public function createGroups()
    {
        try {
            $pythonScript = base_path('python/cluster_groups.py');

            $output = shell_exec("python $pythonScript 2>&1");

            if ($output === null) {
                return response()->json(['error' => 'Python script execution failed'], 500);
            }

            $groups = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'error' => 'JSON decode error: ' . json_last_error_msg(),
                    'raw_output' => $output
                ], 500);
            }

            if (isset($groups['error'])) {
                return response()->json(['error' => $groups['error']], 500);
            }

            return response()->json($groups);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
