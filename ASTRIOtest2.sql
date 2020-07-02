SELECT worker.first_name,worker.last_name,car.model,GROUP_CONCAT(child.name)

FROM worker,child,car

WHERE worker.id = car.user_id AND worker.id = child.user_id

GROUP BY(worker.id)
