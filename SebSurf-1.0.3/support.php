<?php
  include_once 'header.php';
 ?>
    <script type="text/javascript">
    document.getElementById("sup").setAttribute("style", "border: solid white; border-radius: 7px; padding: 3px;")
    </script>
    <div class="main" style="width: 500px;">
        <h1>Sebsurf support:</h1>
    </div>
    <style>
        .main p {
            font-size: 10pt;
            max-width: 60%;
            margin: 0 auto;
            line-height: 30px;
        }
        .ol {
            margin: 0 auto;
            max-width: 60%;
        }
    </style>
    
    <?php 
    #<div class="main">
     #   <form action="support.php" method="post">
      #      <button type="submit" name="help">Support</button>
       #     <button type="submit" name="rights">Richtlinien/Strafen</button>
        #</form>
    #</div>
    if (!isset($_POST["rights"])) {?>

    <div class="main">
        <h2>Login:</h2><br>
        <h3>-> Account</h3>
        <p>Solltest du bis jetzt noch über keinen account verfügen,<br>schreibe eine E-Mail über ISurf an: 
        <a href="mailto:sebsurf@isurfstormarn.de?subject=Sebsurf Account">sebsurf@isurfstormarn.de</a></p><br>
        <p>Ein Admin wird dir einen Account erstellen und dir mit deinen Benutzernamen und deinem temporären Passwort 
            antworten.</p><br>
        <h3>-> Account Anmeldeinformationen</h3>
        <p>Mit diesen kannst du dich rechts im Reiter 'Log in' anmelden.</p><br>
        <h3>-> Passwort ändern</h3>
        <p>
            Wenn du angemeldet bist änderst du bitte dein Passwort! Dies geht in dem Tab 'Settings' dort musst du dann in dem 
            zweitem Abschitt dein neues Passwort einmal eingeben und nochmal bestätigen.
        </p>
    </div>
    <div class="main">
        <h2>Events:</h2><br>
        <h3>-> Eintragen/Hinzufügen von Events</h3>
        <p>
            Du kannst deine für die Schule erledigten 'Dienste', wie z.B. Hausaufgaben Hilfe in 
            Form von Events in deinen Kalender eintragen.
            Dies geht folgendermaßen:<br>
            <span style="font-size: 12pt;">
            1. Melde dich mit deinem Account an<br>
            2. Gehe im Menu auf 'Events'<br>
            3. Trage die Informationen des Events/der einzigen Tätigkeit<br> im zweiten Abschnitt der Seite ein <br>(du kannst das Feld mit 'ID' leer lassen)<br>
            4. Klicke auf 'Hinzufügen'<br></span>
        </p><br>
        <h3>-> Bearbeiten von Events</h3>
        <p>
            Diese kannst du auch wieder bearbeiten, indem du auf der gleichen Seite bei dem Feld 'ID' die ID des Events 
            eingibst, diese bekommst du aus der oben stehenden Tabelle, und die Informationen, die du verändern willst 
            in die restlichen Felder eingibst.
        </p>
    </div>
    <div class="main">
        <h2>Accountinformationen:</h2><br>
        <h3>-> Nickname/Spitzname bearbeiten</h3>
        <p>Dies kannst du auch in den Einstellungen bzw. 'Settings' hier musst du nur oben deinen neuen Spitznamen eintragen und Abschicken.</p><br>
        <p style="color: red;">Dein 'Nickname' wird nur für Cosmetische Zwecke der Website verwendet und kann nicht von anderen Benutzern eingesehen werden!</p>
    </div>
    <div class="main">
        <h2>Administrator Support:</h2><br>
        <h3>-> Power-Stufen</h3>
        <p>40+ 'Datacenter'&'Teams' zugriff</p>
        <p>50+ 'Datacenter' alle Teams bearbeiten</p>
        <p>80+ 'Teams' alle Teams bearbeiten</p>
        <p>100+ Management zugriff</p>
        <p>110+ User Rolle bearbeiten</p>
        <p>115+ Updates/Versions veröffentlichen</p>
        <p>126+ Unbegrenzte Rechte</p>
    </div>
    <?php
    } else {?>
    <div class="main">
        <h2>Richtlinien:</h2><br>
        <h3>§1 Administrator Recht</h3>
        <p>1. Administratoren der Website haben unbegrenzes Recht über die Website entscheidungen zu treffen und Benutzer, wie Events zu verwalten!</p>
        <p>2. Administratoren können jegliche Entscheidungen treffen, die sie für richtig halten und müssen sich für nichts rechtfertigen, 
            was nicht äußere Rechte/Gesetzte verletzt!
        </p><br>
        <h3>§2 Teamleitungs/Teammoderator recht</h3>
        <p>1. Teammoderatoren sind voll verantwortlich für ihr moderiertes Team!</p>
        <p>2. Teammoderatoren haben so auch volles Recht<br> über die Verwaltung der Benutzer in Team!</p>
        <p>3. Teammoderatoren haben das volle Recht über alle Events der Teammitglieder, welche sie für dieses Team eingetragen haben!</p><br>
        <h3>§3 Nicknames</h3>
        <p>1. Gesellschaftlich unerwünschte oder politisch inkorrekte Nicknames sind verboten!</p>
    </div>
    <div class="main">
        <h2>Strafen:</h2><br>
        <p>1. Verwarnung</p>
        <p>2. Account deaktivierung für 3 Tage</p>
        <p>3. Account deaktivierung für 7 Tage</p>
        <p>4. Account deaktivierung für 14 Tage</p>
        <p>5. Permanente Account deaktivierung</p><br>
        <p style="color: red;">Administratoren sind an diese Reihenfolge nicht gebunden, sie gilt nur als Richtwert!</p>
    </div>
    <?php
    }?>