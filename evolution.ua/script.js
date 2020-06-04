import "./node_modules/materialize-css/dist/css/materialize.min.css";
import "./node_modules/materialize-css/dist/js/materialize.min.js";
import "./node_modules/wowjs/css/libs/animate.css";
import "/style.scss";
import { exists } from "fs";

document.body.onload = function () {
  setTimeout(() => {
    var preloader = document.getElementById("preloader");
    if (!preloader.classList.contains("done")) {
      preloader.classList.add("done");
    }
  }, 1000);
};

new WOW().init();

let sideMenu = document.querySelectorAll(".sidenav");
window.M.Sidenav.init(sideMenu);

document.addEventListener("DOMContentLoaded", function () {
  let tooltip = document.querySelectorAll(".tooltipped");
  let toolTipInstances = window.M.Tooltip.init(tooltip);
  let modalWindow = document.querySelectorAll(".modal");
  let modalWindowinstances = M.Modal.init(modalWindow);
  let select = document.querySelectorAll("select");
  let selectInstances = M.FormSelect.init(select);
});

let startTestBtn = document.querySelector("#start-test-btn");
let questionsBlock = document.querySelector(".question-block");
let testNotice = document.querySelector(".test-notice");
let continueBtn = document.querySelector(".continue-btn");
let callBackForm = document.querySelector("#callback-form");
let navBar = document.querySelector("nav");
const questions = document.querySelectorAll(".question");

startTestBtn.addEventListener("click", function () {
  testNotice.classList.add("hide");
  this.classList.add("hide");
  questionsBlock.classList.remove("hide");
});

continueBtn.addEventListener("click", function () {
  questionsBlock.classList.add("hide");
  this.classList.add("hide");
  callBackForm.classList.remove("hide");
});

function fixNavBar() {
  if (window.scrollY > 500) {
    navBar.classList.add("fixed");
  } else {
    navBar.classList.remove("fixed");
  }
}

window.addEventListener("scroll", function () {
  fixNavBar();
});

fixNavBar();

const anchors = document.querySelectorAll('a[href*="#"]');

for (let anchor of anchors) {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();

    const blockID = anchor.getAttribute("href");
    if (blockID.length > 1) {
      document.querySelector(blockID).scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    }
  });
}

const phoneInputObject = document.querySelectorAll(".phone");

for (let phoneInput of phoneInputObject) {
  phoneInput.addEventListener("keydown", function (event) {
    if (event.key != "Backspace" && event.key != "Delete") {
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105)
      ) {
        event.returnValue = false;
      }
      if (phoneInput.value.length >= 13) {
        event.returnValue = false;
      }
      if (
        phoneInput.value.length === 3 ||
        phoneInput.value.length === 7 ||
        phoneInput.value.length === 10
      ) {
        phoneInput.value = phoneInput.value + "-";
      }
    }
  });
}

const radioButtons = document.querySelectorAll("input[type=radio]");
const stateObject = new Object();
for (let radioButton of radioButtons) {
  radioButton.addEventListener("change", function () {
    if (radioButton.checked) {
      stateObject[radioButton.name] = radioButton.checked;
    }
    if (Object.keys(stateObject).length == questions.length) {
      continueBtn.classList.remove("disabled");
    } else {
      continueBtn.classList.add("disabled");
    }
  });
}

const btnLoaders = document.querySelectorAll("button.send-btn");

for (let btnLoader of btnLoaders) {
  btnLoader.addEventListener("click", function (e) {
    if (this.parentElement.querySelector(".name").value == "") {
      e.preventDefault();
      M.toast({
        html: "&#10071; Введите Ваше имя &#10071;",
      });
      return;
    }
    if (this.parentElement.querySelector(".phone").value == "") {
      e.preventDefault();
      M.toast({
        html: "&#10071; Введите номер телефона &#10071;",
      });
      return;
    }
    if (
      !validateEmail(
        this.parentElement.querySelector("input[type=email]").value
      )
    ) {
      e.preventDefault();
      M.toast({
        html: "&#10071; Введите корректный электронный адрес &#10071;",
      });
      return;
    }
    if (
      this.parentElement.parentElement.parentElement.querySelector(
        ".came-from-select"
      ).selectedIndex == 4 &&
      this.parentElement.parentElement.parentElement.querySelector(
        ".friend-name"
      ).value == ""
    ) {
      e.preventDefault();
      M.toast({
        html: "&#10071; Введите пожалуйста имя и фамилию друга &#10071;",
      });
      return;
    }
    if (!this.parentElement.querySelector(".agree-box").checked) {
      e.preventDefault();
      M.toast({
        html:
          "&#10071; Подтвердите согласие о передаче контактных данных &#10071;",
      });
      return;
    }
    this.classList.add("disabled");
    this.firstElementChild.innerHTML = "Идет отправка...";
    this.querySelector(".btn-loader").classList.add("lds-circle");
    let object = this;
    e.preventDefault();
    grecaptcha.ready(function () {
      grecaptcha
        .execute("6LdnFPsUAAAAAA2mDd712HZDnMtW5O8Yyd5XX3XO", {
          action: "submit",
        })
        .then(function (token) {
          object.parentElement.querySelector(
            "input[name=g-recaptcha-response]"
          ).value = token;
          object.parentElement.parentElement.parentElement
            .querySelector("form")
            .submit();
        });
    });
  });
}

function validateEmail(mail) {
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
    return true;
  }
  return false;
}

const cameFromSelectors = document.querySelectorAll(".came-from-select");

for (let cameFromSelector of cameFromSelectors) {
  cameFromSelector.addEventListener("change", function () {
    if (this.options.selectedIndex == 4) {
      this.parentElement.parentElement.parentElement
        .querySelector(".friend-input-box")
        .classList.remove("hide");
    } else {
      this.parentElement.parentElement.parentElement
        .querySelector(".friend-input-box")
        .classList.add("hide");
    }
  });
}
