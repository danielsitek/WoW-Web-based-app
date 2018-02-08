<?php

require_once( 'config.php' );

// in this area your need to fill all variables

$conn = new mysqli( SERVER_NAME, DB_USER_NAME, DB_USER_PASS, DB_SCHEMA );
$conn->set_charset('utf8');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}


// in this part i'm making all SQL queries

function fetch_cheapest_item( $item_id, $quantity, $conn ) {

    $sql = "SELECT owner, (buyout)/10000/{$quantity} as MIN FROM auctions where item={$item_id} and quantity={$quantity} ORDER BY buyout LIMIT 1"; //Starlight Rose cheapest price x200 stack
    $res = $conn->query($sql);

    if ( !$res ) {
        printf("Errormessage: %s\n", $conn->error);
    }

    if ($res && $res->num_rows > 0) {
        // output data of each row
        while($row = $res->fetch_assoc()) {

            return (object) array(
                'price' => $row["MIN"],
                'owner' => $row["owner"],
            );

        }
    }

    return (object) array(
        'price' => null,
        'owner' => null,
    );
}

function fetch_sum_item( $item_id, $quantity, $conn ) {

    $sql = "SELECT sum(quantity) as SUM FROM auctions where item={$item_id} and quantity={$quantity} "; //Starlight Rose cheapest price x200 stack
    $res = $conn->query($sql);

    if ( !$res ) {
        printf("Errormessage: %s\n", $conn->error);
    }

    if ($res && $res->num_rows > 0) {
        // output data of each row
        while($row = $res->fetch_assoc()) {

            return (object) array(
                'sum' => $row["SUM"],
            );

        }
    }

    return (object) array(
        'sum' => null,
    );
}


// Fetch data for every item.

$Starlight_Rose = fetch_cheapest_item( 124105, 200, $conn)->price;

$Fjarnskaggl = fetch_cheapest_item( 124104, 200, $conn)->price;

$Dreamleaf = fetch_cheapest_item( 124102, 200, $conn)->price;


$Wispered_Pact = fetch_cheapest_item( 127847, 1, $conn)->price;
$Wispered_Last_Seller = fetch_cheapest_item( 127847, 1, $conn)->owner;

$Wispered_Pact_Q = fetch_sum_item( 127847, 1, $conn)->sum;


// Calculates here

$Wisper_Crafting_Cost = round( ( ( $Dreamleaf * 10 ) + ( $Fjarnskaggl * 10 ) + ( $Starlight_Rose ) * 7 ), 2 ) / 1.4802;
$Wisper_Profit = ( $Wispered_Pact - $Wisper_Crafting_Cost ) - ( $Wispered_Pact - $Wisper_Crafting_Cost ) * 0.05;


// closing connection to SQL 

$conn->close();


// let's show our data

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="//wow.zamimg.com/widgets/power.js"></script>
    <script>
        var wowhead_tooltips = {
            "colorlinks": true,
            "iconizelinks": true,
            "renamelinks": true
        };
    </script>
    <style type="text/css">
        html {
            font-family: "Arial", Gadget, sans-serif;
            font-size: 1rem;
            line-height: 1.45;
        }
        body {
            padding: 1rem;
        }
        .tg {
            border-collapse: collapse;
            border-spacing: 0;
            border-color: #ccc;
            font-size: inherit;
        }
        .tg td {
            padding: 10px 4px;
            border-style: none;
            border-width: 1px;
            overflow: hidden;
            word-break: normal;
            border-color: #ccc;
            color: #333;
            background-color: #fff;
        }
        .tg th {
            font-weight: normal;
            padding: 10px 4px;
            border-style: none;
            border-width: 1px;
            overflow: hidden;
            word-break: normal;
            border-color: #ccc;
            color: #333;
            background-color: #f0f0f0;
        }
        .tg .tg-9nbt {
            text-align: center;
            vertical-align: top;
        }
        .tg .tg-9right {
            text-align: right;
            vertical-align: top;
        }
        .tg .tg-9left {
            text-align: left;
            vertical-align: top;
        }
        .tg .tg-9center {
            text-align: center;
            vertical-align: top;
        }
        a, u {
            text-decoration: none;
        }
    </style>
</head>
<body>

<p>
    <a href="//www.wowhead.com/item=124105" target="_blank" class="q3">Starlight Rose</a> x200 price = <?php echo $Starlight_Rose; ?>
</p>
<p>
    <a href="//www.wowhead.com/item=124104" target="_blank" class="q3">Fjarnskaggl</a> x200 price = <?php echo $Fjarnskaggl; ?>
</p>
<p>
    <a href="//www.wowhead.com/item=124102" target="_blank" class="q3">Dreamleaf</a> x200 price = <?php echo $Dreamleaf; ?>
</p>
<p>Flask of the Wispered Pact x1 price = <?php echo $Wispered_Pact; ?></p>
<p>Flask of the Wispered Pact crafting cost = <?php echo $Wisper_Crafting_Cost; ?></p>
<p>Profit = <?php echo $Wisper_Profit; ?></p>
<p>Who sell cheapest one? = <?php echo $Wispered_Last_Seller; ?></p>

<h1> Category: Alchemy</h1>

<table class="tg" style="table-layout: fixed; width: 980px">
    <colgroup>
        <col style="width: 15px">
        <col style="width: 150px">
        <col style="width: 31px">
        <col style="width: 55px">
        <col style="width: 50px">
        <col style="width: 60px">
        <col style="width: 40px">
        <col style="width: 70px">
        <col style="width: 120px">
    </colgroup>
    <caption>Alchemy: Legion Flasks</caption>
    <thead>
    <tr>
  	    <th class="tg-9nbt">!</th>
        <th class="tg-9nbt">Item name:</th>
        <th class="tg-9right">Stack:</th>
        <th class="tg-9right">Low buy:</th>
        <th class="tg-9right">$/1:</th>
        <th class="tg-9right">Craft 200:</th>
        <th class="tg-9right">Available:</th>
        <th class="tg-9center">Profit:</th>
        <th class="tg-9left">Seller:</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <?php
                if ( 10 > $Wisper_Profit || "Yoshyoka" === $Wispered_Last_Seller ) {
                    echo '<img src="1.gif">';
                } else {
                    echo '<img src="2.gif"gif>';
                }
                ?>
            </td>
            <td>
                <a href="//www.wowhead.com/item=127847" target="_blank" class="q3" rel="gems=23121&amp;ench=2647&amp;pcs=25695:25696:25697">
                    Flask of the Whispered Pact
                </a>
            </td>
            <td align="right">
                1
            </td>
            <td align="right">
                <?php echo round($Wispered_Pact,2); ?>
                <img src="gold.png">
            </td>
            <td align="right">
                <?php echo round($Wispered_Pact,2); ?>
                <img src="gold.png">
            </td>
            <td align="right">
                <?php echo round($Wisper_Crafting_Cost,2); ?>
                <img src="gold.png">
            </td>
            <td align="right">
                <?php echo $Wispered_Pact_Q; ?>
            </td>
            <td align="right">
                <strong>
                    <?php
                    if ( 0 < $Wisper_Profit ) {
                        echo sprintf( '<span style="color: forestgreen;">%s</span>', round( $Wisper_Profit, 2 ) );
                    } else {
                        echo sprintf( '<span style="color: red;">%s</span>', round( $Wisper_Profit, 2 ) );
                    }
                    ?>
                    <img src="gold.png">
                </strong>
            </td>
            <td>
                <?php
                if ( 'Yoshyoka' === $Wispered_Last_Seller ) {
                    echo sprintf( '<span style="color: forestgreen;">%s</span>', $Wispered_Last_Seller );
                } else {
                    echo sprintf( '<span style="color: red;">%s</span>', $Wispered_Last_Seller );
                }
                ?>
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>
