#!/bin/bash
# login_logs.sh - accesos SSH (exitosos y fallidos)

LOGFILE="/var/log/secure"

echo "Últimos 200 intentos de login:"
grep -E "sshd|authentication failure|Accepted|Failed|Invalid user" "$LOGFILE" | tail -n 200

echo
echo "Resumen de fallidos por IP:"
grep "Failed password" "$LOGFILE" | awk '{for(i=1;i<=NF;i++) if($i=="from") print $(i+1)}' | sort | uniq -c | sort -rn | head -n 20

