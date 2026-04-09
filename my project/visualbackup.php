<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <center>
    <h1>using the Train number</h1>
    <form action="visualreadtrain.php" method="post"  id="trainForm">
    <input type="text" placeholder="Train Number" name="train_no" id="trainno" value="<?php echo isset($train_no) ? htmlspecialchars($train_no) : ''; ?>">
    <button type="submit" name="btn1" >search</button>
    </form>
    </center>
    <script>
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

document.addEventListener("DOMContentLoaded", function()
{    
    asktrain();
});

function asktrain() 
{
    const utterance = new SpeechSynthesisUtterance("Please say Train Number.");
    utterance.lang = "en-IN";
    speechSynthesis.speak(utterance);

    utterance.onend = function() 
    {
        let recognition = new SpeechRecognition();
        recognition.lang = "en-IN";
        recognition.interimResults = false;
        recognition.continuous = false;

        recognition.start();

        recognition.onresult = function(event)
        {
            let speechResult = event.results[0][0].transcript.trim();
            speechResult = speechResult.replace(/\.$/, '').replace(/\s+/g, '');
            if(speechResult)
            {
                document.getElementById('trainno').value = speechResult.toUpperCase();
                document.getElementById('trainForm').submit();
            }
            else
            {
                asktrainAgain();
            }
            
        };
        recognition.onend = function() 
        {
            const noField = document.getElementById('trainno').value.trim();
            if (!noField) 
            {
                asktrainAgain();
            }
        };
    };
}
function asktrainAgain() 
{
    const retryUtterance = new SpeechSynthesisUtterance("I didn't catch that");
    retryUtterance.lang = "en-IN";
    speechSynthesis.speak(retryUtterance);
    retryUtterance.onend = function() 
    {
    asktrain();
    };
}
    </script>
</body>
</html>