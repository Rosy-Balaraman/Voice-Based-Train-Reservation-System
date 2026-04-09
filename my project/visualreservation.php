<?php
session_start();
include 'include.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            background-color: white;
            font-family: Arial, sans-serif; 
            color: #333; 
            margin: 0; 
            padding: 0;
            display: flex;
            flex-direction: column; 
            align-items: center; 
            justify-content: center; 
            height: 100vh; 
        }

        h1 {
            color: navy; 
            font-size: 2.5em; 
            margin-bottom: 20px; 
        }

        form {
            background-color: #f2f2f2;
            padding: 20px; 
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px; 
        }

        input[type="text"] {
            width: 100%; 
            padding: 10px; 
            margin: 10px 0; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            font-size: 1em; 
        }

        button {
            background-color: navy;
            color: white; 
            padding: 10px;
            border: none; 
            border-radius: 4px; 
            font-size: 1em;
            cursor: pointer; 
            transition: background-color 0.3s; 
            width: 100%; 
        }

        button:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }

    </style>
</head>
<body>
<h1><center>BOOK TICKET</center></h1><br>
    <form action="visualsearch.php" method="post">        
        <input type="text" name="from" placeholder="please say your start station" id="from" ><br>
        <input type="text" name="to" placeholder="please say your destination" id="to"><br> 
        <input type="text" name="date" placeholder="enter the date" id="date"><br>
        <input type="text" name="class" placeholder="please say your class" id="class"><br>
        <button type="submit">Submit</button>
    </form>
<script>
 const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

document.addEventListener("DOMContentLoaded", function() {
    saywelcom();
});
function saywelcom()
{
    const welcometext = "Book a ticket";
    const welcome= new SpeechSynthesisUtterance(welcometext);
    welcome.lang = "en-IN";
    speechSynthesis.speak(welcome);

    welcome.onend = function()
    {
        askfrom();
    };
}
function convertDateToDB(spokenDate) {
    const months = {
        "january": "01", "february": "02", "march": "03", "april": "04",
        "may": "05", "june": "06", "july": "07", "august": "08",
        "september": "09", "october": "10", "november": "11", "december": "12"
    };

    let parts = spokenDate.toLowerCase().split(" ");
    
    if (parts.length === 3)
    {
        const day = parts[0].padStart(2, '0');
        const month = months[parts[1].toLowerCase()]; 
        const year = parts[2]; 

        if (day && month && year)
        {
            return `${year}-${month}-${day}`;
        }
    }
    
    return "";
}

function askfrom() 
        {
            const utterance = new SpeechSynthesisUtterance("Please say your start station.");
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
                    speechResult = speechResult.replace(/\.$/, '');
                    if(speechResult)
                    {
                        document.getElementById('from').value = speechResult;
                    }
                    else
                    {
                        askfromAgain();
                    }
                    
                };
                recognition.onend = function() 
                {
                    const nameField = document.getElementById('from').value.trim();
                    if (!nameField) 
                    {
                        askfromAgain();
                    }
                    else
                    {
                        askto();
                    }
                };
            };
        }
        function askfromAgain() 
        {
            const retryUtterance = new SpeechSynthesisUtterance("I didn't catch that");
            retryUtterance.lang = "en-IN";
            speechSynthesis.speak(retryUtterance);
            retryUtterance.onend = function() 
            {
            askfrom();
            };
        }
        function askto()
        {
             const utterance1 = new SpeechSynthesisUtterance("Please say your destination station");
            utterance1.lang = "en-IN";
            speechSynthesis.speak(utterance1);

            utterance1.onend = function() 
            {
                let recognition1= new SpeechRecognition();
                recognition1.lang = "en-IN";
                recognition1.interimResults = false;
                recognition1.continuous = false;

                recognition1.start();

                recognition1.onresult = function(event)
                {
                    let speechResult1 = event.results[0][0].transcript.trim();
                    speechResult1 = speechResult1.replace(/\.$/, '');
                    if(speechResult1)
                    {
                    document.getElementById('to').value=speechResult1;       
                    }
                    else
                    {
                        asktoAgain();
                    }
                    
                };
                recognition1.onend = function() {
                    const toField = document.getElementById('to').value.trim();
                    if (!toField) 
                    {
                        asktoAgain();
                    }
                    else
                    {
                        askdate();
                    }
                };
            };
        }
        function asktoAgain() 
        {
            const retryUtterance1 = new SpeechSynthesisUtterance("I didn't catch that");
            retryUtterance1.lang = "en-IN";
            speechSynthesis.speak(retryUtterance1);
            retryUtterance1.onend = function() 
            {
            askto();
            };
        }
        function askdate()
        {
             const utterance2 = new SpeechSynthesisUtterance("Please say the journey date in the format: day, month, year. For example, 20 September 2024.");
            utterance2.lang = "en-IN";
            speechSynthesis.speak(utterance2);

            utterance2.onend = function() 
            {
                let recognition2= new SpeechRecognition();
                recognition2.lang = "en-IN";
                recognition2.interimResults = false;
                recognition2.continuous = false;

                recognition2.start();

                recognition2.onresult = function(event)
                {
                    let speechResult2 = event.results[0][0].transcript.trim();
                    speechResult2 = speechResult2.replace(/\.$/, '');
                    if(speechResult2)
                    { 
                        const formattedDate = convertDateToDB(speechResult2);
                        if (formattedDate) 
                        {
                        document.getElementById('date').value = formattedDate;
                        console.log(formattedDate);
                        } 
                        else
                        {
                        askdateAgain();
                        }
                    }
                    else 
                    {
                    askdateAgain();
                    }
                };
                recognition2.onend = function() {
                    const dateField = document.getElementById('date').value.trim();
                    if (!dateField) 
                    {
                        askdateAgain();
                    }
                    else
                    {
                        askclass();
                    }
                };
            };
        }
        function askdateAgain() 
        {
            const retryUtterance2 = new SpeechSynthesisUtterance("I didn't catch that");
            retryUtterance2.lang = "en-IN";
            speechSynthesis.speak(retryUtterance2);
            retryUtterance2.onend = function() 
            {
            askdate();
            };
        }

        function askclass()
        {
            const utterance3 = new SpeechSynthesisUtterance("Please say your class");
            utterance3.lang = "en-IN";
            speechSynthesis.speak(utterance3);

            utterance3.onend = function() 
            {
                let recognition3= new SpeechRecognition();
                recognition3.lang = "en-IN";
                recognition3.interimResults = false;
                recognition3.continuous = false;

                recognition3.start();

                recognition3.onresult = function(event) 
                {
                let speechResult3 = event.results[0][0].transcript.trim();
                speechResult3 = speechResult3.replace(/[.,!?;:]/g, '');
                if(speechResult3) 
                {
                    speechResult3 = speechResult3.toLowerCase();
                    document.getElementById('class').value = speechResult3; 
                } 
                else 
                {
                    askclassAgain();
                }
        };
                recognition3.onend = function() 
                {
                    const classField = document.getElementById('class').value.trim();
                    if (!classField) 
                    {
                        askclassAgain();
                    }
                    else
                    {
                        another();
                    }
                };
            };
        }
        function askclassAgain() 
        {
            const retryUtterance3 = new SpeechSynthesisUtterance("I didn't catch that");
            retryUtterance3.lang = "en-IN";
            speechSynthesis.speak(retryUtterance3);
            retryUtterance3.onend = function() 
            {
            askclass();
            };
        }
        function another()
        {
            document.querySelector('form').submit();
        }

   
</script>

</body>
</html>