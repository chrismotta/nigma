select 
	d.date, p.name as affiliate, 
	sum(conv_api) as daily_conv, 
	IF( conv.count_conv is not null, conv.count_conv, 0 ) as conv_log, 
	IF( conv.count_conv is not null, sum(conv_api)-conv.count_conv, sum(conv_api) ) as diff,
	dv.vectors_id as vector,
	v.rate as vector_rate,	
	c.external_rate as ext_rate, 
	d.spend as daily_spend,
	conv.spend as conv_spend,
	d.revenue as daily_revenue,
	conv.revenue as conv_revenue

from daily_report d 
	left join (
		select 
			date(cl.date) as date_conv, 
			pr.id as prov_conv, 
			count(co.id) count_conv, 
			SUM(
				CASE 
					WHEN vl.vectors_id IS NOT NULL AND cv.rate IS NOT NULL AND co.id IS NOT NULL THEN 
						cv.rate
					WHEN co.id IS NOT NULL AND cc.external_rate IS NOT NULL THEN 
						cc.external_rate
					ELSE 0
				END
			) as spend,		
			SUM(
				CASE 
					WHEN ( oc.model_adv="CPC" OR oc.model_adv="CPV" ) AND oc.rate IS NOT NULL THEN
						oc.rate 
					WHEN ( oc.model_adv="CPA" OR oc.model_adv="CPL" OR oc.model_adv="CPI" ) AND co.id IS NOT NULL AND oc.rate IS NOT NULL THEN
						oc.rate 
					ELSE 0
				END
			) as revenue
		from conv_log co 
			left join clicks_log cl on co.clicks_log_id = cl.id 
			left join providers pr on cl.providers_id=pr.id 
			left join vectorsLog vl on vl.clicks_log_id=cl.id
			left join vectors cv on vl.vectors_id = cv.id
			left join campaigns cc on cc.id=cl.campaigns_id
			left join opportunities oc on oc.id=cc.opportunities_id
		where 
			year(cl.date)=2016 and 
			month(cl.date)=9 and 
			pr.type="Affiliate" 
		group by 
			date(cl.date), vl.vectors_id, cl.providers_id ) 
		conv on conv.date_conv=d.date and conv.prov_conv=d.providers_id and conv.vector=v.id
	left join 
		providers p on p.id=d.providers_id
	left join 
		daily_report_vectors dv on dv.daily_report_id=d.id  
	left join 
		vectors v on dv.vectors_id = v.id
	left join
		campaigns c on d.campaigns_id=c.id

where 
	year(d.date)=2016 and 
	month(d.date)=9 and 
	p.type = "Affiliate" 
group by d.date, dv.vectors_id, d.providers_id;