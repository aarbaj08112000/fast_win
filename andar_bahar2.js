var random_order_array =[];
$(document).ready(function () {
  var winner = "";
  var user_winner_details = [];

  $("#everyoneOrder").on("click", function () {
    $("#everyoneOrderDetails").addClass("active shadow-lg");
    $(this).addClass("blue_border shadow-lg");
    $("#myOrder").removeClass("blue_border shadow-lg");
    $("#myOrderDetails").removeClass("active shadow-lg");
    $("#everyoneOrder-btn").removeClass("text-muted");
    $("#myOrder-btn").addClass("text-muted");
    getOrders(game_id);
  });
  $("#test").on("click", function () {
    $('#payment').modal('show');
  });

  $("#myOrder").on("click", function () {
    $("#everyoneOrderDetails").removeClass("active shadow-lg");
    $("#myOrderDetails").addClass("active shadow-lg");
    $(this).addClass("blue_border shadow-lg");
    $("#everyoneOrder").removeClass("blue_border shadow-lg");
    // getOrders(game_id, userid);
    $("#everyoneOrder-btn").addClass("text-muted");
    $("#myOrder-btn").removeClass("text-muted");
  });

  $(".toast").css("visibility", "hidden");
  var totalSeconds_val = 45;
  var totalSeconds = totalSeconds_val; // Initial countdown time in seconds
  var interval = 1000; // Interval in milliseconds
  var end_interval = 5;
  var countdown;
  var restarting = false;
  var game_id = "";
  var ander_win_index = '';
  var baher_win_index = '';
  var lastMove = "";


  generateGameID();

  let currentGameId = "";

  // Start the countdown initially
  startCountdown();

  $("#bidForm").submit(function (e) {
    e.preventDefault();
    var formData = $(this).serialize();

    var currentBalance = $("#balance").html().replace(",", "");

    currentBalance = currentBalance.replace("₹ ", "");
    var newBalance = parseInt(currentBalance) - parseInt($("#join_amount").val());
    $("#balance").html("₹ " + Number(newBalance).toLocaleString("en-IN").replace(/,/g, ","));
    formData = formData + "&newBalance=" + newBalance;

    $.ajax({
      type: "POST",
      url: "andar_bahar_backend.php",
      data: formData,
      success: function (response) {
        response = JSON.parse(response);
        if(response.msg =="Low Balance"){
          toastMessage(response.msg);
        }else{
          toastMessage("You have bet with " + userSelect);
        }


        getOrders(game_id);
        $('#payment').modal('hide');
      },
      error: function (error) {
        console.error("Error:", error);
      },
    });
  });
  /* add functionality for increase decrease point */

  $(".minus-total-point").click(function () {
    var total_point = parseInt($("#total-point").val());
    if(total_point > 1){
      total_point--;
      $("#total-point").val(total_point)
      var selected_money = parseInt($(".amount-btn-box.active .amount_price").val())
      if(selected_money > 0){
        var total_joining_amount = total_point*selected_money
        $(".total_box_value").html(total_joining_amount);
        $("#join_amount").val(total_joining_amount)
      }


    }

  })
  $(".plus-total-point").click(function () {
    var total_point = parseInt($("#total-point").val());
    if(total_point > 0){
      total_point++;
      $("#total-point").val(total_point);
      var selected_money = parseInt($(".amount-btn-box.active .amount_price").val())
      if(selected_money > 0){
        var total_joining_amount = total_point*selected_money
        $(".total_box_value").html(total_joining_amount);
        $("#join_amount").val(total_joining_amount)
      }

    }

  })
  $("#total-point").on("input",function(){
    var total_point = parseInt($(this).val())
    if(!(total_point > 0)){
      total_point = 1;
      $(this).val(1)
    }
    var selected_money = parseInt($(".amount-btn-box.active .amount_price").val())
    if(selected_money > 0){
      var total_joining_amount = total_point*selected_money
      $(".total_box_value").html(total_joining_amount);
      $("#join_amount").val(total_joining_amount)
    }

  })
  $(".btn-group-toggle .amount-btn-box").on("click",function(){
    $(".btn-group-toggle .amount-btn-box").removeClass("active")
    $(this).addClass("active");
    var selected_money = parseInt($(this).find(".amount_price").val())
    var total_point = parseInt($("#total-point").val())
    var total_joining_amount = total_point*selected_money
    $(".total_box_value").html(total_joining_amount);
    $("#join_amount").val(total_joining_amount)
  })
  $(".closebtn").click(function () {
    $('#payment').modal('hide');
  })
  $("#winnr_declear .close-btn").click(function () {
    $('#winnr_declear').modal('hide');
  })



  /* add functionality for increase decrease point */
  $(".closeModal").click(function () {
    var modal_pop = $(".bidding-modal");
    modal_pop.animate({
      height: "0px",
    },
    500
  );
  setTimeout(function () {
    modal_pop.hide();
  }, 100);
});
$(".closeRuleModal").click(function () {
  var modal_pop = $(".rule-modal");
  modal_pop.animate({
    height: "0px",
  },
  590
);
setTimeout(function () {
  modal_pop.hide();
}, 100);
});
$("#rule_confirm").click(function () {
  var modal_pop = $(".rule-modal");
  modal_pop.animate({
    height: "0px",
  },
  590
);
setTimeout(function () {
  modal_pop.hide();
}, 100);
});

var userSelect = "";
var user = $(".rule").data("user");
$(".join").on("click", function () {

  /* open new popup */
  $('#payment').modal('show');
  var user = $(".closeModal").data("userid");
  $(".userId").val(user);
  var periodid = $(".gameid").text();
  $(".periodField").val(periodid);
  userSelect = $(this).data("id");
  $(".selectField").val(userSelect);

  $(".paymentheader").attr("id",userSelect.toLowerCase())
  $(".paymentheader .modal-title").html("Join " +userSelect)

  // console.log("you have selected " + userSelect);
  if (userSelect == "Andar") {
    $("#bidding-modal-title").css("color", "#0d6efd");
  } else if (userSelect == "Bahar") {
    $("#bidding-modal-title").css("color", "#dc3545");
  } else {
    $("#bidding-modal-title").css("color", "#fd7e14");
  }
  $("#bidding-modal-title").text("Join " + userSelect);


  var formData = {
    frontuserid: $(".closeModal").data("userid"),
  };



  var first_elemet = $("#bidForm .btn-group-toggle .amount-btn-box").eq(0);
  var amount1 = parseInt($(first_elemet).find(".amount_price").val());
  if(amount1 > 0){
    $("#bidForm .btn-group-toggle .amount-btn-box").removeClass("active")
    $("#bidForm .btn-group-toggle .amount-btn-box").eq(0).addClass("active")
    $(".total_box_value").html(amount1)
    $("#join_amount").val(amount1)
    $("#total-point").val("1")
    $(".total-point-box").html("1");
  }


  $.ajax({
    type: "POST",
    url: "andar_bahar_backend.php",
    data: formData,
    success: function (response) {
      response = JSON.parse(response);
      $("#balance").html("₹ " + Number(response.balance).toLocaleString("en-IN").replace(/,/g, ","));
    },
    error: function (error) {
      console.error("Error:", error);
    },
  });

});

$("#rule").on("click", function () {
  var modal_pop = $(".rule-modal");
  modal_pop.show();
  modal_pop.animate({
    height: "590px",
  },
  500
);
});


var a = 2000;
const ratio = 5000 / 2000; // Ratio of a and b
const b = a / ratio;
const c = b / 20;
var upCard = "";
setTimeout(function () {
  upCard = $(".upImage").data("cardtomatch");

}, 1000);

// Your deck array
var deck = [
  'AS', '2S', '3S', '4S', '5S', '6S', '7S', '8S', '9S', '10S', 'JS', 'QS', 'KS',
  'AD', '2D', '3D', '4D', '5D', '6D', '7D', '8D', '9D', '10D', 'JD', 'QD', 'KD',
  'AC', '2C', '3C', '4C', '5C', '6C', '7C', '8C', '9C', '10C', 'JC', 'QC', 'KC',
  'AH', '2H', '3H', '4H', '5H', '6H', '7H', '8H', '9H', '10H', 'JH', 'QH', 'KH'
];


function generateGameID() {
  var currentDate = new Date();
  var year = currentDate.getFullYear();
  var month = String(currentDate.getMonth() + 1).padStart(2, "0"); // Adding leading zero if needed
  var day = String(currentDate.getDate()).padStart(2, "0"); // Adding leading zero if needed

  // Constructing the game ID in yyyymmdd format
  var gameId = year + month + day;

  var newGameId = "";
  $.ajax({
    type: "POST",
    url: "andar_bahar_backend.php?get", // Replace with your PHP script to fetch record count
    success: function (response) {
      response  = JSON.parse(response)
      // console.log(response)
      var flag = 0;
      var timer_seconds = '';
      if(parseInt(response.pending_id) > 0){
        game_id = newGameId= response.pending_id
        var totalRecords = parseInt(game_id.replace(gameId, ''));
        // console.log(response,"re")
        var game_card = response.game_card;
        if(response.winer !="no_any_result"){
          end_interval = response.enterval_value;
          winer = response.winer;
          lastMove = response.lastMove;
        }
        if(game_card != "" && game_card != null && game_card != undefined){
          changeUpperCard(game_card)
        }

        timer_seconds = response.timer;

        totalRecords--;
      }else{
        var totalRecords = parseInt(response['count']); // Parse the response as an integer
        game_id = newGameId = gameId + String(totalRecords + 1).padStart(2, "0");
        flag = 1;
      }
      getOrders(newGameId, userid);
      currentGameId = newGameId;
      if(flag == 1){
        addNewGameRecord();

      }

      $(".gameid").html(newGameId);


      //generate random orders in 5 sec
      setInterval(function () {
        var period_id = newGameId;
        generateRandomOrder(period_id);
      }, 5000);


      var order_box = $(".all_records #order_box_"+newGameId);
      if(order_box.length == 0){
        var order_id_val = totalRecords < 10 ? "0"+day+String(totalRecords + 1) : day+String(totalRecords + 1);
        order_id_val = order_id_val.substring(3)
        // console.log(order_id_val);
        $(".all_records").append('<div class="rcd" id="order_box_'+newGameId+'"><div class="sc q"><div class="result-box">?</div><div class="text-dark">' + order_id_val + "</div></div></div>");
      }
      // You can use newGameId as needed here wait a sec
      if(timer_seconds != ""){
        totalSeconds = timer_seconds;
      }else{
        // const d = new Date();
        // let seconds = 60 - d.getSeconds();
        totalSeconds = 45;
        // console.log(totalSeconds);
      }

      if (totalSeconds <= 10) {
        $(".join").addClass("disabled");
      } else {
        $(".join").removeClass("disabled");
      }
      startCountdown();

    },
    error: function (xhr, status, error) {
      console.error("Error fetching record count:", error);
    },
  });
  return newGameId;
}

function startCountdown() {
  clearInterval(countdown); // Clear any existing countdown
  countdown = setInterval(function () {
    if (totalSeconds <= -1) {
      getWiner(game_id);
    } else {
      updateSeconds(totalSeconds);
      var minutes = Math.floor(totalSeconds / 60);
      var seconds = totalSeconds % 60;
      updateDigit("minutes-ten", Math.floor(minutes / 10));
      updateDigit("minutes-one", minutes % 10);
      updateDigit("seconds-ten", Math.floor(seconds / 10));
      updateDigit("seconds-one", seconds % 10);

      if (totalSeconds <= 10) {
        $('#payment').modal('hide');
        $(".join").addClass("disabled");
      } else {
        $(".join").removeClass("disabled");
      }

      totalSeconds--;
    }
  }, interval);
}

// change upper image
function changeUpperCard(card) {
  var upper_card_random_num = getRandomNumber();
  upCard = upper_card_random = deck[upper_card_random_num];
  if(card != '' && card != undefined && card != null){
    upCard = upper_card_random = card;
  }
  $(".upImage").attr("data-cardtomatch",upCard);
  cardImageSrc = "image/" + upper_card_random + ".png";
  $(".upImage").attr("src", cardImageSrc);
  // upCard = deck['random'];
  var mid_card_random = deck[getRandomNumber()];
  while (!(upCard != mid_card_random)) {
    mid_card_random = deck[getRandomNumber()];
  }
  // console.log(mid_card_random,upCard,upper_card_random_num);
  mid_card_random = mid_card_random;
  var cardImage = $(".toggleCard");
  cardImageSrc = "image/" + mid_card_random + ".png";
  cardImage.attr("src", cardImageSrc);
}

function getRandomNumber() {
  var random_num = Math.floor(Math.random() * 52) + 0;
  return random_num;
}

function startGame() {

  var last_move_val = lastMove == "" ? "right" : lastMove;

  if(winner == "Andar"){
    if(last_move_val == 'right'){
      if(end_interval % 2 == 0){
        ander_win_index = 1;
      }else{
        ander_win_index = 0;
      }
    }else if(last_move_val == 'left'){
      if(end_interval % 2 == 0){
        ander_win_index = 0;
      }else{
        ander_win_index = 1;
      }
    }
  }else if(winner == 'Bahar'){
    if(last_move_val == 'right'){
      if(end_interval % 2 == 0){
        baher_win_index = 0;
      }else{
        baher_win_index = 1;
      }
    }else if(last_move_val == 'left'){
      if(end_interval % 2 == 0){
        baher_win_index = 1;
      }else{
        baher_win_index = 0;
      }
    }
  }

  // console.log(last_move_val,ander_win_index)
  // if(winner != "Nothing"){
  startAnimation();
  // }
  // console.log("Countdown reached 0");
  clearInterval(countdown);
  // After reaching 0, wait for 10 seconds and restart
  setTimeout(function () {
    totalSeconds = totalSeconds_val; // Resetting totalSeconds to restart the countdown

    restarting = true;
    // console.log("sending backend call");
    if (restarting) {
      // AJAX call to add a new record in the MySQL table
      setTimeout(function () {
        generateGameID();
      }, 5000);
    }
    restarting = false;
  }, 10000); // Wait for 10 seconds (10000 milliseconds) before restarting
}
var flippedCardArray = [];
function animateCard(cardIndex) {
  end_interval--;

  if (cardIndex === 52) {
    setTimeout(startAnimation, 3000);
    return;
  }
  var cardImage = $(".toggleCard");
  var card_name = deck[cardIndex];
  if (winner == "Andar" && lastMove == "right" && end_interval == ander_win_index) {
    card_name = upCard;
  } else if (winner == "Bahar" && lastMove == "left" && end_interval == baher_win_index) {
    card_name = upCard;
  } else {
    if (card_name == upCard) {
      var cardIndexVal = cardIndex;
      while (cardIndexVal == cardIndex) {
        cardIndexVal = Math.floor(Math.random() * deck.length);
      }
      card_name = deck[cardIndexVal];
    }
  }
  var cardImageSrc = "image/" + card_name + ".png";
  cardImage.attr("src", cardImageSrc);
  // console.log("deck[cardIndex]",deck[cardIndex]);
  var flippedCard = "";
  flippedCard = deck[cardIndex];
  setTimeout(function () {
    // console.log(flippedCard,upCard)
    if (flippedCard != upCard) {
      if (lastMove == "left") {
        cardImage.removeClass("move-left");
        cardImage.addClass("move-right");
        // console.log("added right");
        lastMove = "right";
      } else if (lastMove == "right") {
        cardImage.removeClass("move-right");
        cardImage.addClass("move-left");
        // console.log("added left again");
        lastMove = "left";
      } else {
        lastMove = "left";
        cardImage.addClass("move-left");
        // console.log("added left");
      }
      // console.log("not match");
    } else {

    }
  }, b);


  setTimeout(function () {
    cardImage.attr("src", "");
    cardIndex = Math.floor(Math.random() * deck.length);
    // console.log(cardIndex,$.inArray(cardIndex, flippedCardArray),flippedCardArray)
    if ($.inArray(cardIndex, flippedCardArray) != -1) {
      var cardIndexVal = cardIndex;
      while (cardIndexVal == cardIndex) {
        cardIndexVal = Math.floor(Math.random() * deck.length);
      }
      cardIndex = cardIndexVal;
    }
    // cardIndex = Math.floor(Math.random() * deck.length
    if (end_interval != 0) {
      swipeCardNumber(end_interval)
      animateCard(cardIndex);
      flippedCardArray.push(cardIndex);

      // console.log(flippedCardArray)
    } else {
      swipeCardNumber(end_interval)
      end_interval = 5;
      $("#order_box_"+game_id+" .sc").removeClass("q")
      $("#order_box_"+game_id+" .sc .result-box").html(winner[0])
      $("#order_box_"+game_id+" .sc").addClass(winner[0].toLowerCase())
      /* declair winner */

      if (user_winner_details.user_result =="winner") {
        $("#winnr_declear .paymentheader").attr("id",user_winner_details.winer.toLowerCase())
        $("#winnr_declear #winner_result").text("WIN")
        $("#winnr_declear .win-latter span").removeClass("a b t")
        $("#winnr_declear .win-latter span").html(user_winner_details.winer[0]).addClass(user_winner_details.winer[0].toLowerCase())
        $("#winnr_declear .game_id_box").html(game_id)
        $("#winnr_declear .user_amount").html(user_winner_details.user_amount)
        var winner_amount = user_winner_details.user_wining_amount * 0.02;
        winner_amount = user_winner_details.user_wining_amount - winner_amount;
        $("#winnr_declear .user_wining_amount").html("+₹"+winner_amount.toFixed(2))
        $("#winnr_declear .total_amount").html(user_winner_details.game_amount)
        $("#winnr_declear .selcted_winner_class").removeClass("andar tie bahar")
        $("#winnr_declear .selcted_winner_class").addClass(user_winner_details.winer.toLowerCase())
        $("#winnr_declear .selcted_winner_value").html(user_winner_details.winer)
        $("#winnr_declear .crown_image").show();
        $("#winnr_declear .star-img").show();

        $("#winnr_declear").modal('show')
      }else if (user_winner_details.user_result =="loss") {
        $("#winnr_declear #winner_result").text("LOSS")
        $("#winnr_declear .paymentheader").attr("id",user_winner_details.winer.toLowerCase())
        $("#winnr_declear .win-latter span").removeClass("a b t")
        $("#winnr_declear .win-latter span").html("L").addClass(user_winner_details.winer[0].toLowerCase())
        $("#winnr_declear .crown_image").hide();
        $("#winnr_declear .star-img").hide();
        $("#winnr_declear .game_id_box").html(game_id)
        $("#winnr_declear .user_amount").html(user_winner_details.user_amount)
        $("#winnr_declear .user_wining_amount").html(user_winner_details.user_wining_amount )
        $("#winnr_declear .total_amount").html(user_winner_details.game_amount)
        $("#winnr_declear .selcted_winner_class").removeClass("andar tie bahar")
        $("#winnr_declear .selcted_winner_class").addClass(user_winner_details.selected_value.toLowerCase())
        $("#winnr_declear .selcted_winner_value").html(user_winner_details.selected_value)
        $("#winnr_declear").modal('show')

      }else{
        toastMessage(" Winner's are " + winner);
      }
      // toastMessage(" Winner's are " + winner);
      // $("#winnr_declear .paymentheader").attr("id",winner.toLowerCase())
      // $("#winnr_declear .win-latter span").html(winner[0]).addClass(winner[0].toLowerCase())
      // $("#winnr_declear .game_id_box").html(game_id)
      // $("#winnr_declear").modal('show')
      /* declair winner */

      getOrders(game_id, userid);
      updateWiner(game_id,winner)
      changeUpperCard();
      flippedCardArray = [];


    }

    setTimeout(function () {

      cardImage.removeClass("move-left");
      cardImage.removeClass("move-right");

    }, c);
  }, a);
}

// Function to start the animation sequence
function startAnimation() {
  // preventDefault();
  animateCard(0); // Start animating the cards from index 0
}
function addNewGameRecord(){
  $.ajax({
    type: "POST",
    url: "andar_bahar_backend.php",
    data: {
      // Prepare data for insertion into the table
      gameid: game_id, // Generating game ID based on conditions
      action: "addRecord", // Indicate the action for the PHP script
    },
    success: function (response) {
      response  = JSON.parse(response)
      if(response.game_card != ""){
        // console.log(response.game_card);
        changeUpperCard(response.game_card)
      }
      toastMessage("New Game Started.");
      // console.log("New record added successfully!");
    },
    error: function (xhr, status, error) {
      // console.error("Error adding a new record:", error);
    },
  });
}
function updateSeconds(seconds){
  $.ajax({
    type: "POST",
    url: "andar_bahar_backend.php",
    data: {
      // Prepare data for insertion into the table
      game_id_val: game_id, // Generating game ID based on conditions
      seconds: seconds, // Indicate the action for the PHP script
    },
    success: function (response) {

    },
    error: function (xhr, status, error) {
      // console.error("Error adding a new record:", error);
    },
  });
}
function getWiner(currentGameId) {
  var params = {
    gameId: currentGameId,
    lastMove:lastMove
  };
  $.ajax({
    type: "POST",
    url: "andar_bahar_backend.php",
    data: params,
    success: function (response) {
      response = JSON.parse(response);
      // console.log(response,"winer")
      winner = response.winer;
      user_winner_details = response;
      end_interval = response.enterval_value;
      lastMove = response.lastMove
      startGame(winner);
    },
    error: function (error) {
      console.error("Error:", error);
    },
  });
}

function updateWiner(currentGameId,winner) {
  var params = {
    game_id: currentGameId,
    winner:winner,
    type:"update",
    status:"Inactive"
  };
  $.ajax({
    type: "POST",
    url: "andar_bahar_backend.php",
    data: params,
    success: function (response) {
      // response = JSON.parse(response);
    },
    error: function (error) {
      console.error("Error:", error);
    },
  });
}
function swipeCardNumber(enterval_value) {

  var params = {
    game_id: currentGameId,
    enterval_value:enterval_value,
    lastMove:lastMove
  };
  $.ajax({
    type: "POST",
    url: "andar_bahar_backend.php",
    data: params,
    success: function (response) {
      // response = JSON.parse(response);
    },
    error: function (error) {
      console.error("Error:", error);
    },
  });
}

// Delay the initial animation by 45 seconds
// setTimeout(startAnimation, 15000);

function updateDigit(digitId, value) {
  $("#" + digitId).text(value);
}

function toggleBorder() {
  $("#everyoneOrder").toggleClass("active");
  $("#myOrder").toggleClass("active");
}

function toggleBorders() {
  $("#myOrder").toggleClass("active");
  $("#everyoneOrder").toggleClass("active");
}

function toastMessage(msg) {
  $(".toast-body").text(msg);
  $(".toast").css("visibility", "visible");
  setTimeout(function () {
    $(".toast").css("visibility", "hidden");
  }, 3000);
}
// getOrders(generateGameID(),userid);
function getOrders(period_id, user_id) {

  var active_tab = $(".order_tabs .blue_border").attr("id");
  var formData = {
    period_id: period_id,
  };
  if(active_tab == "myOrder"){
    formData['user_id'] =  user_id;
  }


  // console.log(formData);
  $.ajax({
    type: "POST",
    url: "andar_bahar_backend.php",
    data: formData,
    success: function (response) {
      var data = JSON.parse(response);
      response = data["data"];
      // console.log(response);


      $(".everyoneOrderList").html("");

      if (user_id !== "" && period_id !== "") {
        // $(".myOrderlist").html("");
       $('.myOrderlist .default_data:first').prevAll().remove();
        for (let i = 0; i < response.length; i++) {
          response[i]["points"] = response[i]["amount"];
          $('.myOrderlist .default_data:first').before(`
            <tr>
            <td>${response[i]["period_id"]}</td>
            <td>${
              response[i]["betting_type"] === "Andar"
              ? '<div class="sc a"><div class="">A</div></div>'
              : response[i]["betting_type"] === "Bahar"
              ? '<div class="sc b"><div class="">B</div></div>'
              : '<div class="sc t"><div class="">T</div></div>'
            }
            </td>
            <td>₹ ${response[i]["points"] === "" ? 0 : response[i]["points"]}</td>
            <td class="text-center timer_icon p-0"><i class="fa fa-clock-o" ></i></td>
            <td class="text-center timer_icon p-0"><i class="fa fa-clock-o" ></i></td>
            </tr>
            `);
          }
        }



          if (period_id !== "") {
            for (let i = 0; i < random_order_array.length; i++) {
              $(".everyoneOrderList").prepend(`
                <tr>
                <td>${random_order_array[i]["period_id"]}</td>
                <td>**${random_order_array[i]["user_id"]}</td>
                <td>${
                  random_order_array[i]["betting_type"] === "Andar"
                  ? '<div class="sc a"><div class="">A</div></div>'
                  : random_order_array[i]["betting_type"] === "Bahar"
                  ? '<div class="sc b"><div class="">B</div></div>'
                  : '<div class="sc t"><div class="">T</div></div>'
                }
                </td>
                <td>₹ ${random_order_array[i]["amount"]}</td>
                </tr>
                `);
              }

              for (let i = 0; i < response.length; i++) {
                $(".everyoneOrderList").prepend(`
                  <tr>
                  <td>${response[i]["period_id"]}</td>
                  <td>**${response[i]["user_id"]}</td>
                  <td>${
                    response[i]["betting_type"] === "Andar"
                    ? '<div class="sc a"><div class="">A</div></div>'
                    : response[i]["betting_type"] === "Bahar"
                    ? '<div class="sc b"><div class="">B</div></div>'
                    : '<div class="sc t"><div class="">T</div></div>'
                  }
                  </td>
                  <td>₹ ${response[i]["amount"]}</td>
                  </tr>
                  `);
                }
              }
            },
            error: function (error) {
              console.error("Error:", error);
            },
          });
        }
      });


      function generateRandomOrder(period_id) {
        var cards = ["Andar", "Bahar", "Tie"];
        var betting_type = cards[Math.floor(Math.random() * cards.length)];
        var amount = Math.round((Math.random() * 1000) / 10) * 10;
        var user_id = Math.floor(1000 + Math.random() * 9000);

        if (period_id !== "") {
          random_order_array.push({"period_id":period_id,"user_id":user_id,"betting_type":betting_type,"amount":amount})
          $(".everyoneOrderList").prepend(`
            <tr>
            <td>${period_id}</td>
            <td>**${user_id}</td>
            <td>
            <div class="sc ${betting_type[0].toLowerCase()}"><div class="">${betting_type[0]}</div></div>
            </td>
            <td>₹ ${amount}</td>
            </tr>
            `);
          }
        }
