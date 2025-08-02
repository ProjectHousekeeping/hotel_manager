-- SQLite
select "users"."id", "users"."name", (select count(*) from tarefas where users.id = tarefas.user_id AND tarefas.data >= '2025-07-02' AND tarefas.data <= '2025-08-01') as tarefas_realizadas_count, (SELECT
COALESCE(
CAST((AVG(strftime('%s', tarefas.hora_fim) - strftime('%s', tarefas.hora_inicio)) / 3600) AS INTEGER) || 'h ' ||
CAST(((AVG(strftime('%s', tarefas.hora_fim) - strftime('%s', tarefas.hora_inicio)) % 3600) / 60) AS INTEGER) || 'm',
'0h 0m'
)
FROM tarefas
WHERE tarefas.user_id = users.id
AND tarefas.data >= '2025-07-02'
AND tarefas.data <= '2025-08-01'
) as media_tempo_formatado from "users" where exists (select 1 from "tarefas" where users.id = tarefas.user_id and "tarefas"."data" >= '2025-07-02' and "tarefas"."data" <= '2025-08-01') and "deleted_at" is null and "users"."deleted_at" is null order by "id" asc, "tarefas_realizadas_count" desc