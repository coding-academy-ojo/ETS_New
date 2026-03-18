<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SurveyLog;

class SurveyLogController extends Controller
{
    public function index()
    {
        $surveyLogs = SurveyLog::all(); // You can modify this to fetch logs as needed

        return view('Survey.survey-logs', ['surveyLogs' => $surveyLogs]);
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'trainee_id' => 'required|exists:trainees,id',
            'type' => 'required|string',
            'sent' => 'required|boolean',
        ]);

        // Create a new survey log entry
        SurveyLog::create([
            'trainee_id' => $validatedData['trainee_id'],
            'type' => $validatedData['type'],
            'sent' => $validatedData['sent'],
        ]);

        // Redirect or return a response as needed
    }

    
    public function showLogsBySurveyId($surveyId)
    {
        $surveyLogs = SurveyLog::where('survey_id', $surveyId)->with('survey')->get(); // Assuming there's a relationship between survey_logs and surveys
        return view('survey-logs', ['surveyLogs' => $surveyLogs]); 
    }

    

}
 
