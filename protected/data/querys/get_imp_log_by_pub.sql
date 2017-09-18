select 
date_time, 
d.placement as placement, 
f.unique_id, f.server_ip as user_ip, 
ua.user_agent as user_agent, 
imps as impressions 
from F_Imp_Compact f 
left join D_Supply d on f.D_Supply_id=d.placement_id 
left join user_agent_log ua on f.user_agent = ua.hash 
where 
d.provider like "%190%" 
and date(date_time)="2017-09-07";