function undisableSpeechGen() {
  document.getElementById('generateSpeech').disabled = false;
}

function undisableSpeechCheck() {
  document.getElementById('checkSpeech').disabled = false;
}

function undisableRebuttal() {
  document.getElementById('rebuttalButton').disabled = false;
  document.getElementById('rebuttalButton1').disabled = false;
}

async function typeSentence(sentence, delay = 100) {
    const letters = sentence.split("");
    let i = 0;
    while(i < letters.length) {
        await waitForMs(delay);
        $("#feature-text").append(letters[i]);
        i++
    }
    return;
}

async function deleteSentence() {
    const sentence = $("#feature-text").html();
    const letters = sentence.split("");
    let i = 0;
    while(letters.length > 0) {
        await waitForMs(100);
        letters.pop();
        $("#feature-text").html(letters.join(""));
    }
}


function waitForMs(ms) {
    return new Promise(resolve => setTimeout(resolve, ms))
}

$( document ).ready(async function() {
    while (true) {
        await typeSentence("creativity");
        await waitForMs(2000);
        await deleteSentence();
        await typeSentence("rebuttal");
        await waitForMs(2000);
        await deleteSentence();
        await typeSentence("speech");
        await waitForMs(2000);
        await deleteSentence();
        await typeSentence("argument");
        await waitForMs(2000);
        await deleteSentence();
    }
});

var i = 0;
var speed = 2;

function typeWriter(txt) {
  if (!txt || typeof txt !== 'string') {
    console.error("typeWriter: invalid input", txt);
    return;
  }

  if (i < txt.length) {
    document.getElementById("speechInput").innerHTML += txt.charAt(i);
    i++;
    setTimeout(() => typeWriter(txt), speed);
  }
}

$(document).ready(function () {
    $('#speech-form').on('submit', function (e) {
      e.preventDefault();

      const motion = $('#motion').val();
      const debatetype = $('#debatetype').val();
      const wordlimit = $('#wordlimit').val();
      const side = $('#side').val();

      document.getElementById("speechInput").innerHTML = "Generating speech - this can take up to a minute...";

      $.ajax({
        url: 'generatespeech.php',
        type: 'POST',
        data: {
          motion: motion,
          debatetype: debatetype,
          wordlimit: wordlimit,
          side: side
        },
        success: function (response) {
          document.getElementById("speechInput").innerHTML = "";
          i = 0;
          typeWriter(response)
        },
        error: function () {
          alert('Something went wrong - please try again later.');
        }
      });
    });
});