#!/bin/bash
echo Received paramters $1 $2 $3 $4 $5 $6 >>/var/log/faxmail.log
DATETIME=`date +"%A %d %b %Y %H:%M"`
if [ -e $5.tif ]
then
  echo fax file $5.tif found. Sending email to $4 .... >>/var/log/faxmail.log
  PAGES=$(tiffinfo $5.tif | grep "Page")
  DT=$(tiffinfo $5.tif | grep "Date")
  DTFAX=${DT#*:}
  COUNT=${PAGES#*-}
  rm -f $5.txt
  echo Dear $3, >>$5.txt
  echo >>$5.txt
  echo You have just recieved a new fax document. Details as follow >>$5.txt
  echo >>$5.txt
  echo "From  : "$1 >>$5.txt
  echo "To    : "$2 >>$5.txt
  echo "When  : "$DATETIME '['$DTFAX' ]'>>$5.txt
  echo "Pages : "$COUNT>>$5.txt
  echo  >>$5.txt
  echo >>$5.txt
  echo You can view your faxes online by visiting https://fax.abc.com. Your login name is the full fax number >>$5.txt
  echo  >>$5.txt
  echo Thank you for using $6 >>$5.txt
  echo sendEmail -f $1@fax.abc.com -t $4 -u "New fax received" -a $5.tif -o message-file=$5.txt \ >> /var/log/faxmail.log
  echo "<<<<<<<<<<<<<<<<<<<<---------------->>>>>>>>>>>>>>>>>>>>>>>>>" >> /var/log/faxmail.log
  /usr/local/bin/sendEmail -l /var/log/sendEmail.log -q -s auth.smtp.1and1.fr -xu test@sipcom.fr -xp test123 -f $1@sipcom.fr -t $4 -u "New fax received" -a $5.pdf -o "message-file=$5.txt" 
  #sendEmail -f test@sipcom.fr -t "${EXTNAME} <${EXTEMAIL}>" -u You have a FAX -a /var/lib/asterisk/agi-bin/Sipmobile/fax/${CALLERID(DNID)}/${FAXFILENAME}.pdf -m You have a new FAX. Find attached. -s auth.smtp.1and1.fr -xu test@sipcom.fr -xp test123
else
  rm -f $5.txt
  echo Dear $3, >>$5.txt
  echo >>$5.txt
  echo A call was recieved on your fax line, however no fax was recieved or the attempt failed. Details as follow >>$5.txt
  echo >>$5.txt
  echo "From  : "$1 >>$5.txt
  echo "To    : "$2 >>$5.txt
  #echo $DATETIME >>$5.txt
  echo "When  : "$DATETIME >>$5.txt
  #echo "Pages : "$COUNT>>$5.txt
  echo  >>$5.txt

  echo This notification is for your conveniance, if it is not required please notify your system administrator >>$5.txt
  #echo >>$5.txt
  #echo You can view your faxes online by visiting https://fax.abc.com. Your login name is the full fax number >>$5.txt
  echo  >>$5.txt
  echo Thank you for using $6 >>$5.txt
  echo sendEmail -f $1@fax.abc.com -t $4 -u "Fax reception failed" -o message-file=$5.txt \ >> /var/log/faxmail.log
  echo "<<<<<<<<<<<<<<<<<<<<---------------->>>>>>>>>>>>>>>>>>>>>>>>>" >> /var/log/faxmail.log
  /usr/local/bin/sendEmail -l /var/log/sendEmail.log -q -s 195.219.151.8 -f $1@fax.abc.com -t $4 -u "Fax reception failed" -o "message-file=$5.txt"
  exit
fi
