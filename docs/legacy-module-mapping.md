# Legacy Reference Mapping (completebkp -> realestatecrm-run)

This project keeps an optimized schema and only references the old ASP.NET system for migration compatibility.

## Legacy Modules Reviewed

- chain system/admin/Booking.aspx.cs
- chain system/admin/araziadd.aspx.cs
- chain system/admin/ADDAGENT.aspx.cs
- chain system/admin/brokari.aspx.cs
- totalpaymentdetails.aspx.cs
- arazipayment.aspx.cs
- kishandetails.aspx.cs

## High-Frequency Legacy Tables Found

- customerreg1
- recipt1
- newkishan
- agent
- chainarazi
- arazimap
- arazi30beegha
- brokari
- agentwallet

## Mapping To New Schema

- customerreg1.CUSTREGNO -> registries.customer_reg_no
- customerreg1.DATEOFCOM/date3 -> registries.registry_date
- customerreg1.MODOFPAY -> registries.booking_mode
- customerreg1.CONSAMOUNT -> registries.registry_amount
- customerreg1.INSTSUBPAY -> registries.installment_amount
- customerreg1.downpay -> registries.down_payment
- customerreg1.lockreg -> registries.lock_status
- customerreg1.NOMINEESNAME -> registries.nominee_name
- customerreg1.idcard -> registries.id_card_no
- customerreg1.AMOUNTWORD -> registries.payment_words
- customerreg1.APPNO -> arazis.legacy_arazi_code
- customerreg1.plotno -> arazis.plot_number / plots.title
- customerreg1.mobile2 -> customers.secondary_mobile
- customerreg1.CHECKBY / agentid -> registries.check_by_agent_id / registries.agent_id
- recipt1.RCID/receipt no -> payments.receipt_no
- recipt1.AMOUNTR -> payments.amount
- recipt1.DATE1 -> payments.payment_date
- recipt1 table source marker -> payments.source_table = recipt1
- agent.formid -> agents.form_code
- agent.rank -> agents.rank_title
- agent.agentper -> agents.legacy_percent
- agent.agentid (parent) -> agents.sponsor_agent_id

## Flow Decisions Applied

- Resource routes now exclude show endpoints for consistency with controller set.
- Registry lock/unlock status is explicitly managed with payment lifecycle.
- Seeder now populates legacy-reference fields to support phased import and reporting.
