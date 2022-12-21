// history
$(function () {
  $('button[data-bs-toggle="pill"]').historyTabs();
});

// sidenav
$(document).ready(() => {
  let btn = true;
  $("#toggle-nav").click(() => {

    if (btn !== false) {
      $("#sidenav").css({
        "width": "0",
        "padding": "0"
      } );
      $("#content").css("margin-left", "0");
      $("#sidenav div").css("opacity", ".3");
      btn = false;
    }
    else {
      $("#sidenav").css({
        "width": "250px",
        "padding": "1rem"
      });
      $("#content").css("margin-left", "250px");
      $("#sidenav div").css("opacity", "1");
      btn = true;
    }

  });
})

// live time
function displayClock() {
  const display = new Date().toLocaleTimeString();
  const time = document.getElementById("displayTime");
  time.innerHTML = display;
  setTimeout(displayClock, 1000); 
}
displayClock();

// display date
const d = new Date();
const monthlist = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
const weekdaylist = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
$("#getday").html(`${ monthlist[d.getMonth()] } ${ d.getDate() }, ${d.getFullYear()} | ${weekdaylist[d.getDay()]}`);

// instascan
const preview = document.getElementById("preview");
const value = document.getElementById("value");
const scanner = new Instascan.Scanner({ video: preview });

// console.log(document.forms);
const scan = async () => {

  try {
    const camera = await Instascan.Camera.getCameras();
    camera.map((cam) => {
      console.log(cam);
    });
    if (camera.length > 0) {
      scanner.start(camera[0]);
    }
    else {
      console.log("No Camera Found");
    }

    scanner.addListener("scan", (qrcode) => {
      value.value = qrcode;
      console.log(qrcode);
      document.forms[2].submit();
    });

  } 
  catch (error) {
    console.log("Error:", error);
  }
}

// open camera
$("#scan-qrcode").click(() => {
  scan();
});

// close camera
$("#close").click(() => {
  const camera = Instascan.Camera.getCameras();
  scanner.stop(camera);
  console.log("Close Clicked");
});

// sweet alert
const qrNotrecognized = () => {
  Swal.fire({
    icon: 'error',
    title: 'Qrcode Error',
    text: 'Qrcode Not Recognized',
    timer: '3000',
    showConfirmButton: false,
    allowOutsideClick: false,
    timerProgressBar: true
  }).then((result) => {
    if (result.dismiss === Swal.DismissReason.timer) {
      location.href = 'admin.php';
    }
  });
}

const completed = () => {
  Swal.fire({
    icon: 'info',
    title: `Attendance Completed Today`,
    text: `You're already Time in and Out Today`,
    timer: '3000',
    showConfirmButton: false,
    allowOutsideClick: false,
    timerProgressBar: true
  }).then((result) => {
    if (result.dismiss === Swal.DismissReason.timer) {
      location.href = 'admin.php';
    }
  });
}

