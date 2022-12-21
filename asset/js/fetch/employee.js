const api_fetch = async url => {
  return (await fetch(url)).json();
}
// const data = async () => {
//   const d = await api_fetch("/qr-attendance/asset/php/json/employee-list.php");
//   console.log(d)
// }
// data();

const tbStyle = () => {
  let style = {
    table: {
      'text-transform': 'capitalize',
      'max-height': '300px'
    },
    th: {
      'height': '1rem'
    },
    td: {
      'text-align': 'center',
      'padding': '7px 15px',
      'font-size': '.8rem'
    }
  };
  return style;
}


// employee info list
new gridjs.Grid({
  pagination: {
    limit: 10
  },
  sort: true,
  search: true,
  columns: ['Employee ID', 'Fullname', 'Department', 'Gender', 'Actions'],
  server: {
    url: '/qr-attendance/asset/php/json/employee-list.php',
    then: data => data.map(info => [info.employee_id, `${info.firstname} ${info.lastname}`, info.department, info.gender, 
      gridjs.html(
        "<div class='d-flex gap-2'>" +
          `<a class='btn btn-sm btn-warning' href='display-info.php?id=${ info.information_id }'> <i class='bi bi-pencil-square'></i></a>` +
          `<a class='btn btn-sm btn-danger' href='delete-employee.php?id=${ info.information_id }' onclick = "return confirm('Are you sure you want to delete this record')"> <i class='bi bi-trash3'></i></a>` +
        "</div>"
      ) 
    ])
  },
  style: tbStyle()

}).render(document.getElementById("wrapper"));


// schedule
// const sched = async () => {
//   const d = await api_fetch("/qr-attendance/asset/php/json/schedule.php");
//   console.log(d);
// }
// sched();

new gridjs.Grid({
  sort: true,
  search: true,
  pagination: {
    limit: 10
  },
  columns: [
    'Employee ID','Name', 'Day', 'Time min', 'Time max'
  ],
  server: {
    url: '/qr-attendance/asset/php/json/schedule.php',
    then: data => data.map(info => [info.employee_id, info.fullname, info.day, info.time_min, info.time_max])
  },
  style: tbStyle()

}).render(document.getElementById("daily-sched"));





// present table

new gridjs.Grid({
  sort: true,
  search: true,
  pagination: {
    limit: 10
  },
  columns: [
    'Employee ID', 'Date', 'Name', 'Time in', 'Status'
  ],
  server: {
    url: '/qr-attendance/asset/php/json/present.php',
    then: data => data.map(info => [info.employee_id, info.date, info.fullname, info.time_in, info.status])
  },
  style: tbStyle()

}).render(document.getElementById("present-table"));




// complete table
// const completetBL = async () => {
//   const d = await api_fetch("/qr-attendance/asset/php/json/complete.php");
//   console.log(d);
// }
// completetBL();

new gridjs.Grid({
  sort: true,
  search: true,
  pagination: {
    limit: 10
  },
  columns: [
    'Employee ID', 'Date', 'Name', 'IN', 'OUT', 'Total Hour', 'Status'
  ],
  server: {
    url: '/qr-attendance/asset/php/json/complete.php',
    then: data => data.map(info => [info.employee_id, info.date, info.fullname, info.time_in, info.time_out, info.total_hour, info.status])
  },
  style: tbStyle()

}).render(document.getElementById("complete-table"));


// incomplete table
// const incompleteTbl = async () => {
//   const d = await api_fetch("/qr-attendance/asset/php/json/incomplete.php");
//   console.log(d);
// }
// incompleteTbl();

new gridjs.Grid({
  sort: true,
  search: true,
  pagination: {
    limit: 10
  },
  columns: [
    'Employee ID', 'Date', 'Name', 'Time in', 'Status'
  ],
  server: {
    url: '/qr-attendance/asset/php/json/incomplete.php',
    then: data => data.map(info => [info.employee_id, info.date, info.fullname, info.time_in, info.status])
  },
  style: tbStyle()

}).render(document.getElementById("incomplete-table"));




// late table
// const lateTbl = async () => {
//   const d = await api_fetch("/qr-attendance/asset/php/json/late.php");
//   console.log(d);
// }
// lateTbl();

new gridjs.Grid({
  sort: true,
  search: true,
  pagination: {
    limit: 10
  },
  columns: [
    'Employee ID', 'Date', 'Name', 'IN', 'Status'
  ],
  server: {
    url: '/qr-attendance/asset/php/json/late.php',
    then: data => data.map(info => [info.employee_id, info.date, info.fullname, info.time_in, info.status])
  },
  style: tbStyle()

}).render(document.getElementById("late-table"));



// ontime table
// const ontimetbl = async () => {
//   const d = await api_fetch("/qr-attendance/asset/php/json/ontime.php");
//   console.log(d);
// }
// ontimetbl();
new gridjs.Grid({
  sort: true,
  search: true,
  pagination: {
    limit: 10
  },
  columns: [
    'Employee ID', 'Date', 'Name', 'IN', 'Status'
  ],
  server: {
    url: '/qr-attendance/asset/php/json/ontime.php',
    then: data => data.map(info => [info.employee_id, info.date, info.fullname, info.time_in, info.status])
  },
  style: tbStyle()

}).render(document.getElementById("ontime-table"));




// absent table
new gridjs.Grid({
  sort: true,
  search: true,
  pagination: {
    limit: 10
  },
  columns: [
    'Employee ID', 'Date', 'Name', 'Status'
  ],
  server: {
    url: '/qr-attendance/asset/php/json/absent.php',
    then: data => data.map(info => [info.employee_id, info.date, info.fullname, info.status])
  },
  style: tbStyle()

}).render(document.getElementById("absent-table"));

// const absenttbl = async () => {
//   const d = await api_fetch("/qr-attendance/asset/php/json/absent.php");
//   console.log(d);
// }
// absenttbl();




// attendance-list table
new gridjs.Grid({
  sort: true,
  search: true,
  pagination: {
    limit: 10
  },
  fixedHeader: true,
  columns: [
    'Employee ID', 'Date', 'Name', 'Time In', 'Time Out', 'Total Hour', {
      name: 'Status',
      attributes: (cell) => {
        if (cell) {
          return {
            'className': 'gridjs-td-status gridjs-td'
          }
        }
        
      }
      
    }
  ],
  server: {
    url: '/qr-attendance/asset/php/json/attendance-list.php',
    then: data => data.map(info => [info.employee_id, info.date, info.fullname, info.time_in, info.time_out, info.total_hour, info.status])
  },
  style: tbStyle()

}).render(document.getElementById("attendance-list-table"));

// const attendance_list = async () => {
//   const d = await api_fetch("/qr-attendance/asset/php/json/attendance-list.php");
//   console.log(d);
// }
// attendance_list();






// url auto show sub menu
document.querySelectorAll("section > button").forEach((btn) => {
  btn.addEventListener("click", () => {
    console.log("Clicked");
    window.location.reload();
  });
});


var url = location.href;
var parts = url.split('#');
var lastSegment = parts.pop() || parts.pop();  // handle potential trailing slash
console.log(lastSegment);

const present = "http://localhost/qr-attendance/asset/php/admin.php#present";
const complete = "http://localhost/qr-attendance/asset/php/admin.php#complete";
const incomplete = "http://localhost/qr-attendance/asset/php/admin.php#incomplete";
const absent = "http://localhost/qr-attendance/asset/php/admin.php#absent";
const late = "http://localhost/qr-attendance/asset/php/admin.php#late";
const ontime = "http://localhost/qr-attendance/asset/php/admin.php#ontime";

setTimeout(() => {
  if (
    location.href == present || 
    location.href == complete || 
    location.href ==  incomplete || 
    location.href == absent || 
    location.href == late || 
    location.href == ontime) {

    document.getElementById("sub-monitor").classList.add("show");
    // document.querySelector(".bi-chevron-down").style.transform = "rotate(-90deg)";
    console.log("active");
  }
  else {
    document.getElementById("sub-monitor").classList.remove("show");
  }
}, 100);