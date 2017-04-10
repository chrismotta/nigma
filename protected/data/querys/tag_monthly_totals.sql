# by provider
select s.provider as Publisher, sum(b.revenue) as Revenue, sum(b.cost) as Cost, sum(b.revenue - b.cost) as Profit 
from F_Imp i left join D_Bid b on i.id = b.F_Impressions_id left join D_Supply s on i.D_Supply_ID = s.placement_id  
where year(date_time)=2017 and month(date_time)=1 group by s.provider;

select s.provider as Publisher, count(i.id) as Imps, count(distinct i.unique_id) as Unique, sum(b.revenue) as Revenue, sum(b.cost) as Cost, sum(b.revenue - b.cost) as Profit 
from F_Imp i left join D_Bid b on i.id = b.F_Impressions_id left join D_Supply s on i.D_Supply_ID = s.placement_id  
where year(date_time)=2017 and month(date_time)=1 group by s.provider;

# by advertiser
select d.advertiser as Advertiser, sum(b.revenue) as Revenue, sum(b.cost) as Cost, sum(b.revenue - b.cost) as Profit
from F_Imp i left join D_Bid b on i.id = b.F_Impressions_id left join D_Demand d on i.D_Demand_ID = d.tag_id  
where year(date_time)=2017 and month(date_time)=1 group by d.advertiser;


select s.provider as Publisher, count(i.id) as Impressions, sum(b.revenue) as Revenue, sum(b.cost) as Cost, sum(b.revenue - b.cost) as Profit 
from F_Imp i left join D_Bid b on i.id = b.F_Impressions_id left join D_Supply s on i.D_Supply_ID = s.placement_id  
where date(date_time) between '2017-02-01' and '2017-02-15' group by s.provider;