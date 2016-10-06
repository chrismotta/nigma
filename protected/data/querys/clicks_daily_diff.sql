select 
	d.date, p.name as affiliate, 
	sum(conv_api) as daily_conv, 
	IF(conv.count_conv is not null, conv.count_conv, 0) as conv_log, 
	IF( conv.count_conv is not null, sum(conv_api)-conv.count_conv, sum(conv_api) ) as diff 
from daily_report d 
	left join (
		select 
			date(cl.date) as date_conv, 
			pr.id as prov_conv, 
			count(co.id) count_conv 
		from conv_log co 
			left join clicks_log cl on co.clicks_log_id = cl.id 
			left join providers pr on cl.providers_id=pr.id 
		where 
			year(cl.date)=2016 and 
			month(cl.date)=9 and pr.type="Affiliate" 
		group by 
			date(cl.date),cl.providers_id ) 
		conv on conv.date_conv=d.date and conv.prov_conv=d.providers_id  
	left join 
		providers p on p.id=d.providers_id 
where 
	year(d.date)=2016 and 
	month(d.date)=9 and 
	p.type = "Affiliate" 
group by d.date, d.providers_id;
