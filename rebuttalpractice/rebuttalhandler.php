<?php 

ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 300);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

$api = "https://models.github.ai/inference/chat/completions";
$api_key = "";

function getRebuttalFeedback($speech, $rebuttal, $side, $motion, $debatetype) {
    global $api, $api_key;

    if ($debatetype == "ap") {
        $prompt = "For all intents and purposes, you are a debater in the Asian Parliamentary format. It follows a structured format with three speakers per team with 2 teams total. The Prime Minister (1st Proposition) is responsible for defining the motion, outlining the arguments their team will bring (team’s case), explaining which speakers will present which arguments, and delivering two out of three arguments. The Leader of Opposition (1st Opposition) may challenge the definition (if unfair), outline the opposition’s case, explain their team’s argument distribution, rebut the Prime Minister, and present part of their case. The Deputy Prime Minister (2nd Proposition) and Deputy Leader of Opposition (2nd Opposition) further rebut the opposing side and present additional arguments for their team. The Proposition Whip (3rd Proposition) and Opposition Whip (3rd Opposition) summarize and clarify their side’s case without introducing new arguments but reinforcing key clashes. Finally, the Reply Speakers from both sides deliver a summary of the debate, identifying the most important clashes and explaining why their side wins. The motions always begin with 'This House', referring to a global governing body or a country’s government. The verb following 'This House' determines the type of motion and the approach debaters must take: 'Believes That' (THBT) requires teams to argue whether something is right or wrong; 'Would' (THW) motions go further, requiring action and a mechanism (plan) detailing how it would be implemented; 'Regrets' (THR) motions evaluate past actions based on their present effects; and 'Supports' (THS) motions assess an ongoing trend or policy. Each substantive speaker (1st-3rd) is scored out of 100 points: 40 for content (strength of arguments, rebuttal, logical analysis), 40 for style (tone, persuasion, engagement), and 20 for strategy (speech structure, logical sequencing, time management). Reply speeches are worth 50 points (20 for content, 20 for style, 10 for strategy). The speaker scale runs from 60 to 80, with Exceptional (80) being the highest, followed by Excellent (76-79), Extremely Good (74-75), Very Good (71-73), Good (70), Satisfactory (67-69), Competent (65-66), Pass (61-64), and Improvement Needed (60). Judges assess style by evaluating tone, humor, emotions, speed, volume, and movement while disregarding accents and minor mispronunciations. Content is judged separately from delivery and is assessed based on the strength and empirical importance of arguments, including the effectiveness of rebuttal. Strategy focuses on the logical flow, clarity, and coherence of the speech, ensuring each argument builds upon the previous one and contributes to the team’s case. A first speaker speech follows a structured format: (1) Introduction – A short, engaging opening (15-20 seconds) (2) Stance – Clearly outline the team’s position and motion type (3) Rebuttal – Starting from the 1st Opposition, speakers must refute key points made by the other side, challenging their logic, evidence, and assumptions. (4) Arguments – These should be titled concisely and structured into three key parts but this should not be mentioned, you just have to structure your argument that way: What is the problem? (Explain the issue being addressed.) How will we solve it on our side? (Describe the proposed solution or action.) What are the impacts? (Demonstrate the real-world benefits or harms.) Each argument should be logically structured, persuasive, and well-supported by evidence. (5) Conclusion – End with a strong, memorable closing statement. A well-structured speech ensures clarity, persuasion, and effectiveness in delivering the team’s case. Read this first Speaker speech for side $side for the motion as the 1st Speaker: $motion : $speech. Now, here's some rebuttal to this speech : $rebuttal. GIVE THE TOP 4 THINGS YOU LIKED MOST IN THE REBUTTAL AND TOP 4 THINGS TO IMPROVE IN THE REBUTTAL AS A PLAIN TEXT RESPONSE STARTING WITH HERES MY FEEDBACK or similar AND GOING ON TO MENTION THIS AND RIGHT AFTER HERES MY FEEDBACK MENTION A SCORE OUT OF 100 AND CUT A LOT OFF THE SCORE IF THE REBUTTAL IS IRRELEVANT FOR THE MOTION OR SIDE. Don't mention anything except for the actual feedback in your reply, this is an integration with an API. Your feedback should be about 300-500 words. Add as much reasoning as you can.";
    }
    if ($debatetype == "bp") {
        $prompt = "For all intents and purposes, you are a debate in the British Parliamentary format. It features four teams: Opening Government, Opening Opposition, Closing Government, and Closing Opposition. Each team has two speakers. You are the first speaker of the Opening half. The Prime Minister (Opening Government 1st speaker) defines the motion fairly in the spirit of the debate, presents a clear model if needed, explains their team’s stance, outlines a roadmap, and delivers two fully developed arguments. The Leader of Opposition (Opening Opposition 1st speaker) may challenge the definition if it is abusive, defend the status quo or present an alternative, rebut the Prime Minister’s arguments, and deliver two arguments of their own. Each **constructive argument must include**:  1. A short, assertive **claim** (like a headline)  2. A step-by-step **explanation** (with logical analysis and reasoning)  3. A **clear impact** (why it matters)  4. **Illustration** — Use vivid, realistic examples or description that paints a picture. Show, don’t just tell. This is mandatory. Your **goal is not only to present arguments** but to **outweigh and be more relevant, strategic, and persuasive than the team on the same bench** (e.g., Opening Government must outweigh Closing Government). Be comparative, impactful, and strategic. Follow this exact structure:1. **Introduction** – Short, persuasive opening (10–20 seconds), e.g. “So proud to propose/oppose.”  2. **Definition** – Clearly define key terms in the spirit of the motion. For policy motions (e.g., “This House Would”), include a simple model or plan of action.  3. **Roadmap** – What each speaker will do (just mention what arguments they're doing - like mention the 2 you'll do as first speaker, and 1 which someone else on the team will do as second speaker).  4. **Rebuttal** – (Only if you are Opposition) Respond logically and respectfully to the PM’s arguments.  5. **Two Arguments** – Each must have a claim, detailed logical explanation, illustration (example/story), and impact.  6. **Conclusion** – Finish with a strong, clear line: “For all these reasons and so many more, I have never been prouder to propose/oppose.” Read the speech of the first speaker on side $side for the motion: $motion : $speech. Now, here's some rebuttal to this speech : $rebuttal. GIVE THE TOP 4 THINGS YOU LIKED MOST IN THE REBUTTAL AND TOP 4 THINGS TO IMPROVE IN THE REBUTTAL AS A PLAIN TEXT RESPONSE STARTING WITH HERES MY FEEDBACK or similar AND GOING ON TO MENTION THIS AND RIGHT AFTER HERES MY FEEDBACK MENTION A SCORE OUT OF 100 AND CUT A LOT OFF THE SCORE IF THE REBUTTAL IS IRRELEVANT FOR THE MOTION OR SIDE. Don't mention anything except for the actual feedback in your reply, this is an integration with an API. Your feedback should be about 300-500 words. Add as much reasoning as you can.";
    }

    $data = [
        "messages" => [
            [
                "role" => "system",
                "content" => "DO NOT SAY ANYTHING EXCEPT THE FEEDBACK, THIS IS AN INTEGRATION IN A SOFTWARE WITH AN API"
            ],
            [
                "role" => "user",
                "content" => $prompt
            ],
        ],
        "temperature" => 1.0,
        "top_p" => 1.0,
        "max_tokens" => 1000,
        "model" => "openai/gpt-4.1"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $api_key"
    ]);

    error_log("API Request: " . json_encode($data));
    $response = curl_exec($ch);
    error_log("Raw Response: " . $response);

    $jsonResponse = json_decode($response, true);

    if (curl_errno($ch)) {
        error_log('cURL error: ' . curl_error($ch));
        return 'An error occurred while connecting to the API.';
    }

    if (isset($jsonResponse['choices'][0]['message']['content'])) {
        return $jsonResponse['choices'][0]['message']['content'];
    } else {
        return 'No content found in response.';
    }
}

function getSpeech($motion, $debatetype, $side) {
    global $api, $api_key;

    if ($debatetype == "ap") {
        $prompt = "For all intents and purposes, you are a debate in the Asian Parliamentary format. It follows a structured format with three speakers per team with 2 teams total. The Prime Minister (1st Proposition) is responsible for defining the motion, outlining the arguments their team will bring (team’s case), explaining which speakers will present which arguments, and delivering two out of three arguments. The Leader of Opposition (1st Opposition) may challenge the definition (if unfair), outline the opposition’s case, explain their team’s argument distribution, rebut the Prime Minister, and present part of their case. The Deputy Prime Minister (2nd Proposition) and Deputy Leader of Opposition (2nd Opposition) further rebut the opposing side and present additional arguments for their team. The Proposition Whip (3rd Proposition) and Opposition Whip (3rd Opposition) summarize and clarify their side’s case without introducing new arguments but reinforcing key clashes. Finally, the Reply Speakers from both sides deliver a summary of the debate, identifying the most important clashes and explaining why their side wins. The motions always begin with 'This House', referring to a global governing body or a country’s government. The verb following 'This House' determines the type of motion and the approach debaters must take: 'Believes That' (THBT) requires teams to argue whether something is right or wrong; 'Would' (THW) motions go further, requiring action and a mechanism (plan) detailing how it would be implemented; 'Regrets' (THR) motions evaluate past actions based on their present effects; and 'Supports' (THS) motions assess an ongoing trend or policy. Each substantive speaker (1st-3rd) is scored out of 100 points: 40 for content (strength of arguments, rebuttal, logical analysis), 40 for style (tone, persuasion, engagement), and 20 for strategy (speech structure, logical sequencing, time management). Reply speeches are worth 50 points (20 for content, 20 for style, 10 for strategy). The speaker scale runs from 60 to 80, with Exceptional (80) being the highest, followed by Excellent (76-79), Extremely Good (74-75), Very Good (71-73), Good (70), Satisfactory (67-69), Competent (65-66), Pass (61-64), and Improvement Needed (60). Judges assess style by evaluating tone, humor, emotions, speed, volume, and movement while disregarding accents and minor mispronunciations. Content is judged separately from delivery and is assessed based on the strength and empirical importance of arguments, including the effectiveness of rebuttal. Strategy focuses on the logical flow, clarity, and coherence of the speech, ensuring each argument builds upon the previous one and contributes to the team’s case. A first speaker speech follows a structured format: (1) Introduction – A short, engaging opening (15-20 seconds), along with often/sometimes stating, “So proud to propose/oppose.” (2) Stance – Clearly outline the team’s position and motion type: “What is our stance for this debate? Panel, note that this motion is a (Actor/Policy/Preferred) motion, where **(explain its nature).”** (3) Rebuttal – Starting from the 1st Opposition, speakers must refute key points made by the other side, challenging their logic, evidence, and assumptions. (4) Arguments – These should be titled concisely and structured into three key parts but this should not be mentioned, you just have to structure your argument that way (don't make any headings!): What is the problem? (Explain the issue being addressed.) How will we solve it on our side? (Describe the proposed solution or action.) What are the impacts? (Demonstrate the real-world benefits or harms.) Each argument should be logically structured, persuasive, and well-supported by evidence. (5) Conclusion – End with a strong, memorable closing statement: “For all these reasons and so many more, I have never been prouder to propose/oppose.” A well-structured speech ensures clarity, persuasion, and effectiveness in delivering the team’s case. Write a first Speaker speech for side $side for the motion as the 1st Speaker: $motion. WRITE A SPEECH WITH THE FORMAT I GAVE YOU AND TWO ARGUMENTS PLEASE. Don't mention anything except for the actual speech in your reply, this is an integration with an API. Your speech should be about 400-500 words. Add as much reasoning as you can.";
    }

    if ($debatetype == "bp") {
        $prompt = "For all intents and purposes, you are a debate in the British Parliamentary format. It features four teams: Opening Government, Opening Opposition, Closing Government, and Closing Opposition. Each team has two speakers. You are the first speaker of the Opening half. The Prime Minister (Opening Government 1st speaker) defines the motion fairly in the spirit of the debate, presents a clear model if needed, explains their team’s stance, outlines a roadmap, and delivers two fully developed arguments. The Leader of Opposition (Opening Opposition 1st speaker) may challenge the definition if it is abusive, defend the status quo or present an alternative, rebut the Prime Minister’s arguments, and deliver two arguments of their own. Each **constructive argument must include**:  1. A short, assertive **claim** (like a headline)  2. A step-by-step **explanation** (with logical analysis and reasoning)  3. A **clear impact** (why it matters)  4. **Illustration** — Use vivid, realistic examples or description that paints a picture. Show, don’t just tell. This is mandatory. Your **goal is not only to present arguments** but to **outweigh and be more relevant, strategic, and persuasive than the team on the same bench** (e.g., Opening Government must outweigh Closing Government). Be comparative, impactful, and strategic. Follow this exact structure:1. **Introduction** – Short, persuasive opening (10–20 seconds), e.g. “So proud to propose/oppose.”  2. **Definition** – Clearly define key terms in the spirit of the motion. For policy motions (e.g., “This House Would”), include a simple model or plan of action.  3. **Roadmap** – What each speaker will do (just mention what arguments they're doing - like mention the 2 you'll do as first speaker, and 1 which someone else on the team will do as second speaker).  4. **Rebuttal** – (Only if you are Opposition) Respond logically and respectfully to the PM’s arguments.  5. **Two Arguments** – Each must have a claim, detailed logical explanation, illustration (example/story), and impact.  6. **Conclusion** – Finish with a strong, clear line: “For all these reasons and so many more, I have never been prouder to propose/oppose.” You are the first speaker on side $side for the motion: $motion.  Write a complete, persuasive, logically structured speech of around $wordLimit words.  Do not include any section labels, commentary, or role descriptions — only the full speech text - this is an integration with an API. Write your speech in a maximum of 400-500 words.";
    }

    $data = [
        "messages" => [
            [
                "role" => "system",
                "content" => "DO NOT SAY ANYTHING EXCEPT THE SPEECH, THIS IS AN INTEGRATION IN A SOFTWARE WITH AN API"
            ],
            [
                "role" => "user",
                "content" => $prompt
            ]
        ],
        "temperature" => 1.0,
        "top_p" => 1.0,
        "max_tokens" => 1000,
        "model" => "openai/gpt-4.1"
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $api);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $api_key"
    ]);

    error_log("API Request: " . json_encode($data));
    $response = curl_exec($ch);
    error_log("Raw Response: " . $response);

    $jsonResponse = json_decode($response, true);

    if (curl_errno($ch)) {
        error_log('cURL error: ' . curl_error($ch));
        return 'An error occurred while connecting to the API.';
    }

    if (isset($jsonResponse['choices'][0]['message']['content'])) {
        return $jsonResponse['choices'][0]['message']['content'];
    } else {
        return 'No content found in response.';
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $motion = $_POST['motion'] ?? '';
    $debatetype = $_POST['debatetype'] ?? '';
    $side = $_POST['side'] ?? '';
    $speech1 = $_POST['speech'] ?? '';
    $rebuttal = $_POST['rebuttal'] ?? '';

    if ($speech1 == '') {
        $speech = getSpeech($motion, $debatetype, $side);
        echo $speech;
    } elseif ($speech1 != '') {
        $feedback = getRebuttalFeedback($speech1, $rebuttal, $side, $motion, $debatetype);
        echo $feedback;
    }
}
?>