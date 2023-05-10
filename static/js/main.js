$(document).ready(function() {





$('.brands__item:hidden').attr('data-hide', true);

$(document).on('click','.brands__show', function(){
  $(this).toggleClass('active');
  let dataItem = $(this).siblings('.brands__item[data-hide="true"]');
  if (!$(this).hasClass('active')) {
    dataItem.css({'display':'none'});
  }else {
    dataItem.css({'display':'flex'});
  }
  return false;
});


function compare() {
  $(document).on('click','.js-option', function(){
    $(this).closest('.options').hide().next().show();
    return false;
  });
  $(document).on('click','.js-compare', function(){
    $(this).closest('.compare').hide().prev().show();
    return false;
  });
}

compare();

function optionTable() {
  $(document).on('click', '.engine__open', function(){
    $(this).toggleClass('active');
    $(this).closest('.engine__top').next('.engine__body').slideToggle();
  });
}
  
optionTable();
  
function counterModel() {
  let timeoutId;
  $(document).on('click', '.engine__check input', function(e){
    let count = $('.engine__check input:checked').length;
    if (count > 3) return false;
    let popupCounter = $('.modelCounter');
    popupCounter.addClass('active').find('span').text(count);
    clearTimeout(timeoutId);
    timeoutId = setTimeout(()=> { $(popupCounter).removeClass('active') }, 3000);
    
  });
}
  
counterModel();
  
function changeSaleCounter() {  
  let sum = $('.programs__sum span');
    let sumValue = +sum.text().split(' ').join('');
    let sale = this.value;

    if (this.checked) {
      sum.text(prettify(sumValue + parseInt(sale)));      
    } else {     
        sum.text(prettify(sumValue - parseInt(sale)));    
    }
}
  
function prettify(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}


function startSaleCounter() {
  var programsItems = document.querySelectorAll('.programs__item input');

  for(var i=0; i<programsItems.length; i++) {
    programsItems[i].addEventListener('change', changeSaleCounter);

    if(programsItems[i].checked) {
      let sum = $('.programs__sum span');
      let sumValue = +sum.text().split(' ').join('');
      let sale = programsItems[i].value;
      sum.text(prettify(sumValue + parseInt(sale)));
    } 
  }
}
var hasCounterSale = document.querySelector('.programs__sum span');
if(hasCounterSale) {
  startSaleCounter();
}
  
  
function options() {
  $(document).on('click', '.feature__table tr', function() {
    let cParent = $(this).attr('class');
    $(this).siblings('[data-tr="'+cParent+'"]').toggle(); 
    return false;
  });
}
  
options();
  
function fixedMenu() {
  let nav = $('.header__body');
  let height = $(nav).offset().top;
  $(window).scroll(function(){
    if ($(window).scrollTop() > height){
      $(nav).addClass('fixed');
    } else {
      $(nav).removeClass('fixed');
    }
  });
}
  
fixedMenu();
  
function colorAuto() {
  $(document).on('click', '.colorAuto li', function() { 
    $(this).addClass('active').siblings().removeClass('active');
    var picture = $('.colorAuto .active').attr('picture');
    $('.offers__img img').attr('src', picture);
    return false;
  });
}
  
colorAuto();

$('[data-scroll]').on('click', function(e){
  e.preventDefault();
  let elemId = $(this).data('scroll');
  let elemOffset = $(elemId).offset().top;
  $('html, body').animate({
      scrollTop: elemOffset
  },1000);
  return false;

});
  
function sandwich() {
  let body = $('body');
  $(document).on('click', '.sandwich', function(e) {
    $(this).toggleClass('active');
    if (!$(this).hasClass('active')) {
      $('.mobile-menu').removeClass('active');
      $(body).removeClass('hidden');
    }else {
      $('.mobile-menu').addClass('active');
      $(body).addClass('hidden');
    }
    return false;
  });
};
  
  sandwich();
  
function menuCloseTarget() {
  let body = $('body');
  $(document).click(function(e){
    let burgerMenu = $('.sandwich');
    let mobileMenu = $('.mobile-menu');
    if (!burgerMenu.is(e.target) && mobileMenu.has(e.target).length === 0) {
      $(burgerMenu).removeClass('active');
      $(mobileMenu).removeClass('active');
      $(body).removeClass('hidden');
    }
    //return false;
  });
}
  
menuCloseTarget();
  
  
$('input[type="tel"]').inputmask("+7 (999) 999-99-99");
  
  
$('form').on('click','button[type="submit"]',function() {
  let $forms = $(this).closest('form');
  $.validator.addMethod('phonemask', function (value) {
      return value.replace(/\D+/g, '').length > 10;
  });
  $forms.validate({
      errorClass: 'form-error',
      errorPlacement: function() {
        return true;
      },
      rules: {
          name: {
            minlength: 3
          },
          'price-from': {
            minlength: 3
          },
          'price-to': {
            minlength: 3
          },
          phone: {
            phonemask: true,
            minlength: 10
          }
      }
  });
});

$(document).on('click','.sort__btn', function(){
  $(this).addClass('active').siblings().removeClass('active');
  if ($(this).attr('data-sort') === 'old') {
    $('input[name="typecar"]').val('Заявка на автомобиль с пробегом');
  } else {
    $('input[name="typecar"]').val('Заявка на новый автомобиль');
  }
  return false;
});

 $.choicesInstances = []
$('.choicesCar').each(function(i, item) {
  let carValue = $(item).attr('data-choices');
  let choicesObj =  new Choices(item, {
    itemSelectText:'',
    // shouldSort: false,
    noResultsText: 'Нет вариантов'
  });

  $.choicesInstances.push(choicesObj)

  if (carValue === 'brand') {
    choicesObj.setChoices(
      [
        // { value: 'Honda', label: 'Honda' },
      ], 'value', 'label', false,
    );
  } else if (carValue === 'model') {
    choicesObj.setChoices(
      [
        // {
        //   label: 'Audi',
        //   id: 1,
        //   disabled: false,
        //   choices: [
        //     { value: 'A1', label: 'A1' },
        //     { value: 'A3', label: 'A3' },
        //     { value: 'A4', label: 'A4' },
        //     { value: 'A5', label: 'A5' },
        //     { value: 'Q3', label: 'Q3' },
        //     { value: 'S3', label: 'S3' },
        //   ],
        // },
        // {
        //   label: 'BMW',
        //   id: 2,
        //   disabled: false,
        //   choices: [
        //     { value: 'M5', label: 'M5' },
        //     { value: 'X5', label: 'X5' },
        //     { value: 'X6', label: 'X6' }
        //   ],
        // },
        
      ], 'value', 'label', false,
    );
  }
  
});

// $('.choicesNotSearch').each(function(){
//   new Choices(this, {
//     itemSelectText:'',
//     shouldSort: false,
//     searchChoices: false,
//     searchEnabled: false
//   });
// });


$('.gallery-all').on('click', function(){
  $(this).hide().siblings().fadeIn();
});

function itemAllShow(items) {
  $(document).on('click','.btnShow', function(){
    $(this).hide().closest('body').find(items).fadeIn();
  });
}

itemAllShow($('.new-cars__col'));
itemAllShow($('.trusted__item'));


function filterTabs() {
  $(document).on('click','.filters__tab', function(){
    $(this).addClass('active').siblings().removeClass('active');
    $(this).closest('.filters').find('.filters__list').eq($(this).index()).addClass('active').siblings().removeClass('active');
    return false;
  });
}

filterTabs();

  
function numberPrice() {
  $('.programs__item').on('click',function() {

  });
}
  
numberPrice();

/*function init() {
  let myMap = new ymaps.Map("map", {
    center: [59.824982,30.354379],
    zoom: 10,
      controls: []
  }, {
    searchControlProvider: 'yandex#search'
  });
  
  let myPlacemark = new ymaps.Placemark([59.824982,30.354379], {
      iconCaption: 'Автоцентр "Пулково"'
    }, {
      preset: 'islands#redIcon'
    });
  myMap.geoObjects.add(myPlacemark);
}

ymaps.ready(init); */
    


const swiper = document.querySelector('.swiper');
if (swiper) {
  new Swiper(swiper, {
    speed: 400,
    cssMode: true,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    mousewheel: true,
    keyboard: true,
  });
}

const creditCount = document.getElementById('sliderMonth');
if (creditCount) {
  noUiSlider.create(creditCount, {
    start: 6,
    connect: 'lower',
    range: {
      'min': 0,
      'max': 12
    }
  });
  const rangeMonthInput = document.getElementById('sliderMonthValue');
  creditCount.noUiSlider.on('update', function (values, handle) {
    rangeMonthInput.value = Math.trunc(+values[handle]) + ' ' + 'мес';
  });
}

const payCount = document.getElementById('sliderPay');
if (payCount) {
  noUiSlider.create(payCount, {
    start: 95000,
    connect: 'lower',
    range: {
      'min': 0,
      'max': 1000000
    }
  });
  const rangePayInput = document.getElementById('sliderPayValue');
  payCount.noUiSlider.on('update', function (values, handle) {
    rangePayInput.value = Math.trunc(+values[handle]) + ' ' + '₽';
    
  });
}

//select2
var selectStandard = $(".js-select2-standard");
  if (selectStandard.length !== 0) {

    //Навешиваем select2
    $(".js-select2-standard select").select2({
      theme: 'theme-select2-standard'
    }).on("change", changeChoiseStandardSelect).trigger('change');

    $(".js-select2-standard--no-search select").select2({
      theme: 'theme-select2-standard',
      minimumResultsForSearch: -1
    }).on("change", changeChoiseStandardSelect).trigger('change');

    //ф-ция изменения select-а
    function changeChoiseStandardSelect(event) {
      if (this.value !== "") {
        this.parentNode.classList.add('select2--ok');
        this.classList.add('form__select--ok');

        //создаем событие изменение select
        var changeSelectEvent;
        if (typeof (Event) === 'function') {
          changeSelectEvent = new Event('input', { bubbles: true, cancelable: true });
        } else {
          changeSelectEvent = document.createEvent('Event');
          changeSelectEvent.initEvent('input', true, true);
        }
        // this.closest('.js-select2-standard').querySelector('select').dispatchEvent(changeSelectEvent); //вызываем событие
        this.parentNode.querySelector('select').dispatchEvent(changeSelectEvent); //вызываем событие
      }
    }
  }

  //********** Генерируем комплектации и сравнение авто **********//
  var packagesWrap = document.querySelector('.js-car-packages');

  if (packagesWrap) {

    //Генерируем массив с комплектациями (объектами)
    var packagesList = [{}];
    generatePackagesList();
    function generatePackagesList() {
      var arrPackages = packagesWrap.querySelectorAll('.item-car-packages__item');

      for (var i = 0; i < arrPackages.length; i++) {
        var fullnameValue = arrPackages[i].querySelector('.js-choise-package').value;
        var optionsSafetyValue = [];
        var optionsInteriorValue = [];
        var optionsExteriorValue = [];
        var optionsComfortValue = [];

        var options = arrPackages[i].querySelectorAll('.item-car-packages__info-item');
        for (var s = 0; s < options.length; s++) {
          if (options[s].querySelector('.item-car-packages__info-title').innerText === 'Системы безопасности') {
            var optionsSafetyValueArr = options[s].querySelectorAll('li');
            for (var op = 0; op < optionsSafetyValueArr.length; op++) {
              optionsSafetyValue[op] = optionsSafetyValueArr[op].innerText;
            }
          }
          if (options[s].querySelector('.item-car-packages__info-title').innerText === 'Интерьер') {
            var optionsInteriorValueArr = options[s].querySelectorAll('li');
            for (var op = 0; op < optionsInteriorValueArr.length; op++) {
              optionsInteriorValue[op] = optionsInteriorValueArr[op].innerText;
            }
          }
          if (options[s].querySelector('.item-car-packages__info-title').innerText === 'Экстерьер') {
            var optionsExteriorValueArr = options[s].querySelectorAll('li');
            for (var op = 0; op < optionsExteriorValueArr.length; op++) {
              optionsExteriorValue[op] = optionsExteriorValueArr[op].innerText;
            }
          }
          if (options[s].querySelector('.item-car-packages__info-title').innerText === 'Комфорт') {
            var optionsComfortValueArr = options[s].querySelectorAll('li');
            for (var op = 0; op < optionsComfortValueArr.length; op++) {
              optionsComfortValue[op] = optionsComfortValueArr[op].innerText;
            }
          }
        }


        packagesList[i] = {
          fullname: fullnameValue,
          optionsSafety: optionsSafetyValue,
          optionsInterior: optionsInteriorValue,
          optionsExterior: optionsExteriorValue,
          optionsComfort: optionsComfortValue
        };
      }
    }

    ///******СРАВНЕНИЕ***///
    var listCompareSafety = [];
    var listCompareInterior = [];
    var listCompareExterior = [];
    var listCompareComfort = [];

    //генерируем списки параметров из табл. сравнения
    function generateCompareLists() {
      var arrCompareItems = document.querySelectorAll('.compare__accordion-wrap');
      for (var s = 0; s < arrCompareItems.length; s++) {
        if (arrCompareItems[s].querySelector('.compare__name').innerText === 'Системы безопасности') {
          var arrItems = arrCompareItems[s].querySelectorAll('.compare__td-name');

          for (var count = 0; count < arrItems.length; count++) {
            listCompareSafety[count] = arrItems[count].innerText;
          }
        } else if (arrCompareItems[s].querySelector('.compare__name').innerText === 'Интерьер') {
          var arrItems = arrCompareItems[s].querySelectorAll('.compare__td-name');

          for (var count = 0; count < arrItems.length; count++) {
            listCompareInterior[count] = arrItems[count].innerText;
          }
        } else if (arrCompareItems[s].querySelector('.compare__name').innerText === 'Экстерьер') {
          var arrItems = arrCompareItems[s].querySelectorAll('.compare__td-name');

          for (var count = 0; count < arrItems.length; count++) {
            listCompareExterior[count] = arrItems[count].innerText;
          }
        } else if (arrCompareItems[s].querySelector('.compare__name').innerText === 'Комфорт') {
          var arrItems = arrCompareItems[s].querySelectorAll('.compare__td-name');

          for (var count = 0; count < arrItems.length; count++) {
            listCompareComfort[count] = arrItems[count].innerText;
          }
        }
      }
    }
    generateCompareLists();

    startInputsChangePackages();

    //изменение input-ов в табл. сравнения
    function startInputsChangePackages() {
      // Выбор комплектации в сравнении
      var selectChoiseEquipment = document.querySelectorAll(".js-choise-equipment-compare");
      if (selectChoiseEquipment.length !== 0) {
        for (var i = 0; i < selectChoiseEquipment.length; i++) {
          selectChoiseEquipment[i].addEventListener('change', choiseComparepackage);
        }        
      }
    } 

    //Занесение данных в табл. сравнения
    function choiseComparepackage() {
      var element = this;
      var currentItem;
      var currentSelect = element.name;

      var arrCompareItems = document.querySelectorAll('.compare__accordion-wrap');
      for (var s = 0; s < arrCompareItems.length; s++) {
        if (arrCompareItems[s].querySelector('.compare__name').innerText === 'Системы безопасности') {
          var blocksSafetyArr = arrCompareItems[s].querySelectorAll('.compare__tr');
        } else if (arrCompareItems[s].querySelector('.compare__name').innerText === 'Интерьер') {
          var blocksInteriorArr = arrCompareItems[s].querySelectorAll('.compare__tr');
        } else if (arrCompareItems[s].querySelector('.compare__name').innerText === 'Экстерьер') {
          var blocksExteriorArr = arrCompareItems[s].querySelectorAll('.compare__tr');
        } else if (arrCompareItems[s].querySelector('.compare__name').innerText === 'Комфорт') {
          var blocksComfortArr = arrCompareItems[s].querySelectorAll('.compare__tr');
        }
      }

      if (element.value !== "-") {
        for (var i = 0; i < packagesList.length; i++) {

          if (packagesList[i].fullname === element.value) {
            currentItem = packagesList[i];
          }

        }

        var currentSafety = currentItem.optionsSafety;
        var currentInterior = currentItem.optionsInterior;
        var currentExterior = currentItem.optionsExterior;
        var currentComfort = currentItem.optionsComfort;

        var currentIndex = currentSelect.slice(('equipment-compare-').length, currentSelect.length); //индекс колонки        

        for (var i = 0; i < listCompareSafety.length; i++) {

          if (currentSafety.indexOf(listCompareSafety[i]) !== -1) {
            blocksSafetyArr[i].querySelectorAll('.compare__td')[currentIndex].classList.add('yes');
            blocksSafetyArr[i].querySelectorAll('.compare__td')[currentIndex].classList.remove('no');
          } else {
            blocksSafetyArr[i].querySelectorAll('.compare__td')[currentIndex].classList.remove('yes');
            blocksSafetyArr[i].querySelectorAll('.compare__td')[currentIndex].classList.add('no');
          }
        }

        for (var i = 0; i < listCompareInterior.length; i++) {

          if (currentInterior.indexOf(listCompareInterior[i]) !== -1) {
            blocksInteriorArr[i].querySelectorAll('.compare__td')[currentIndex].classList.add('yes');
            blocksInteriorArr[i].querySelectorAll('.compare__td')[currentIndex].classList.remove('no');
          } else {
            blocksInteriorArr[i].querySelectorAll('.compare__td')[currentIndex].classList.add('no');
            blocksInteriorArr[i].querySelectorAll('.compare__td')[currentIndex].classList.remove('yes');
          }
        }

        for (var i = 0; i < listCompareExterior.length; i++) {

          if (currentExterior.indexOf(listCompareExterior[i]) !== -1) {
            blocksExteriorArr[i].querySelectorAll('.compare__td')[currentIndex].classList.add('yes');
            blocksExteriorArr[i].querySelectorAll('.compare__td')[currentIndex].classList.remove('no');
          } else {
            blocksExteriorArr[i].querySelectorAll('.compare__td')[currentIndex].classList.add('no');
            blocksExteriorArr[i].querySelectorAll('.compare__td')[currentIndex].classList.remove('yes');
          }
        }

        for (var i = 0; i < listCompareComfort.length; i++) {

          if (currentComfort.indexOf(listCompareComfort[i]) !== -1) {
            blocksComfortArr[i].querySelectorAll('.compare__td')[currentIndex].classList.add('yes');
            blocksComfortArr[i].querySelectorAll('.compare__td')[currentIndex].classList.remove('no');
          } else {
            blocksComfortArr[i].querySelectorAll('.compare__td')[currentIndex].classList.add('no');
            blocksComfortArr[i].querySelectorAll('.compare__td')[currentIndex].classList.remove('yes');
          }
        }
      } else {
        var currentIndex = currentSelect.slice(('equipment-compare-').length, currentSelect.length); //индекс колонки

        for (var i = 0; i < listCompareSafety.length; i++) {
          blocksSafetyArr[i].querySelectorAll('.compare__td')[currentIndex].classList.add('no');
          blocksSafetyArr[i].querySelectorAll('.compare__td')[currentIndex].classList.remove('yes');
        }

        for (var i = 0; i < listCompareInterior.length; i++) {
          blocksInteriorArr[i].querySelectorAll('.compare__td')[currentIndex].classList.add('no');
          blocksInteriorArr[i].querySelectorAll('.compare__td')[currentIndex].classList.remove('yes');
        }

        for (var i = 0; i < listCompareExterior.length; i++) {
          blocksExteriorArr[i].querySelectorAll('.compare__td')[currentIndex].classList.add('no');
          blocksExteriorArr[i].querySelectorAll('.compare__td')[currentIndex].classList.remove('yes');
        }

        for (var i = 0; i < listCompareComfort.length; i++) {
          blocksComfortArr[i].querySelectorAll('.compare__td')[currentIndex].classList.add('no');
          blocksComfortArr[i].querySelectorAll('.compare__td')[currentIndex].classList.remove('yes');
        }
      }


    }

    // Доп.
    //Отслеживание клика на блоке с авто (делегируем по родителю, тк item's меняются)
    if (packagesWrap.length !== 0) {
      packagesWrap.addEventListener('click', checkClickOnPackage);
    }

    function checkClickOnPackage(event) {
      if (event.target.closest('.js-choise-package')) {
        changeChoiseCompareCheckbox(event.target.closest('.js-choise-package'));
      }
    }

    //Выбор чек-бокса
    function changeChoiseCompareCheckbox(element) {
      //создаем событие изменение input
      var changeInputEvent;
      if (typeof (Event) === 'function') {
        changeInputEvent = new Event('change', { bubbles: true, cancelable: true });
      } else {
        changeInputEvent = document.createEvent('Event');
        changeInputEvent.initEvent('change', true, true);
      }
      //end создание события

      var compareInputsArr = document.querySelectorAll('.js-choise-equipment-compare');
      if (element.checked) {
        var error = true;
        for (var i = 0; i < compareInputsArr.length; i++) {
          if (compareInputsArr[i].value === '-') {
            compareInputsArr[i].value = element.value;
            compareInputsArr[i].dispatchEvent(changeInputEvent); //вызываем событие изменения Input-а

            error = false;
            return;     
          }
        }
        if (error === true) {
          element.checked = false;
        }
      } else {
        for (var i = 0; i < compareInputsArr.length; i++) {
          if (compareInputsArr[i].value === element.value) {
            compareInputsArr[i].value = "-";
            compareInputsArr[i].dispatchEvent(changeInputEvent); //вызываем событие Input-а

            return;
          }
        }
      }
    }

  }
  //********** End Генерируем комплектации **********//


});





