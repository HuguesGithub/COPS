[GoneJoueur]
select="SELECT id, joueurNom, joueurPrenom, joueurTelephone, joueurStaff, joueurBlesse, joueurLicenceStatut, joueurLicencePayee, joueurLicenceNumero, joueurNumeroMaillot, joueurPoste, joueurPosteNext, dansRoster "
from="FROM wp_8_gones_joueurs "
where="WHERE joueurTelephone LIKE '%s' "
insert="INSERT INTO wp_8_gones_joueurs (joueurNom, joueurPrenom, joueurTelephone, joueurStaff, joueurBlesse, joueurLicenceStatut, joueurLicencePayee, joueurLicenceNumero, joueurNumeroMaillot, joueurPoste, joueurPosteNext, dansRoster) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');"
update="UPDATE wp_8_gones_joueurs SET joueurNom='%s', joueurPrenom='%s', joueurTelephone='%s', joueurStaff='%s', joueurBlesse='%s', joueurLicenceStatut='%s', joueurLicencePayee='%s', joueurLicenceNumero='%s', joueurNumeroMaillot='%s', joueurPoste='%s', joueurPosteNext='%s', dansRoster='%s' "

[GoneNotification]
select="SELECT id, goneJoueurId, typeNotifId, notifiedDate, vue "
from="FROM wp_8_gones_notifications "
where="WHERE goneJoueurId LIKE '%s' AND typeNotifId LIKE '%s' AND vue LIKE '%s' "
insert="INSERT INTO wp_8_gones_notifications (goneJoueurId, typeNotifId, notifiedDate, vue) VALUES ('%s', '%s', '%s', '%s');"
update="UPDATE wp_8_gones_notifications SET goneJoueurId='%s', typeNotifId='%s', notifiedDate='%s', vue='%s' "

[GonePresence]
select="SELECT id, joueurId, presenceDate "
from="FROM wp_8_gones_presence "
where="WHERE joueurId LIKE '%s' AND presenceDate LIKE '%s' "
insert="INSERT INTO wp_8_gones_presence (joueurId, presenceDate) VALUES ('%s', '%s');"
update="UPDATE wp_8_gones_presence SET joueurId='%s', presenceDate='%s' "

[GoneTypeNotification]
select="SELECT id, notificationTitre, notificationIcone "
from="FROM wp_8_gones_type_notification "

[MecAttendee]
select="SELECT attendee_id, post_id, event_id, occurrence, joueur_id, email, first_name, last_name, data, count, verification, confirmation, statuts "
from="FROM wp_8_mec_attendees "
whereId="WHERE attendee_id='%s' "
where="WHERE post_id LIKE '%s' AND event_id LIKE '%s' AND occurrence LIKE '%s' AND joueur_id LIKE '%s' AND statuts LIKE '%s' "
insert="INSERT INTO wp_8_mec_attendees (post_id, event_id, occurrence, joueur_id, email, first_name, last_name, data, count, verification, confirmation, statuts) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');"
update="UPDATE wp_8_mec_attendees SET post_id='%s', event_id='%s', occurrence='%s', joueur_id='%s', email='%s', first_name='%s', last_name='%s', data='%s', count='%s', verification='%s', confirmation='%s', statuts='%s' "

[MecDate]
select="SELECT id, post_id, dstart, dend, tstart, tend, public, date_title, date_content, data_roster "
from="FROM wp_8_mec_dates "
where="WHERE post_id LIKE '%s' AND dstart LIKE '%s' AND tstart > '%s' AND public LIKE '%s' "
wherePrev="WHERE post_id LIKE '%s' AND dstart LIKE '%s' AND tstart < '%s' AND public LIKE '%s' "
whereByPostIds="WHERE post_id LIKE '%s' AND dstart LIKE '%s' AND tstart > '%s' AND public LIKE '%s' AND post_id IN _STR_INIDS_ "
insert="INSERT INTO wp_8_mec_dates (post_id, dstart, dend, tstart, tend, public, date_title, date_content, data_roster) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');"
update="UPDATE wp_8_mec_dates SET post_id='%s', dstart='%s', dend='%s', tstart='%s', tend='%s', public='%s', date_title='%s', date_content='%s', data_roster='%s' "

[MecEvent]
select="SELECT id, post_id, start, end, repeat, interval, year, month, day, week, weekday, weekdays, days, not_in_days, time_start, time_end "
from="FROM wp_8_mec_events "
where="WHERE post_id LIKE '%s' "
