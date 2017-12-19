# USER STORY

As an employee 
I want to mark used products to my workshift 
so that I can report them to my employer. 

## What is a product?

Product can have following properties:
    - product identifier (integer)
    - name or title
    - product type (default, standard etc)
    - unit (kg, kpl, min, pcs)
    - price without tax
    - amount
    - tax (alv, usually 24%)
    - timestamp when added
    - description or other simple additional message

When product is submitted... it is important that the product values cannot be changed. We want to prevent situation where somebody change original product properties and it will have impact already logged products. Basically product in workshift is a copy of original product information.

One workshift can have zero to many product.

### Scenario 1

Given valid workshift
And submit one valid product to workshift
Then workshift product count is 1
and latest product equals given submitted product.

`(I have not written every detailed steps, for example valid product means that all needed information is given that is described in product propterties.)`

### Scenario 2

Given valid workshift
And submit two different valid product to workshift
Then workshift product count is 2
and latest product equals second submitted product
and earliest product equals first submitted product.

### Scenario 3

Given valid workshift
And submit zero valid product to workshift
Then workshift product count is 0.

# USER STORY 

As an employee 
I want to mark project to my workshift 
so my employer can attach costs to right client.

## What is a project?

Project is piece of information how employers can organize workshift entries.

One workshift can be linked only one or zero project.

### Scenario 1

Given valid workshift
And attach it to "Project 1"
Then workshift attached project is "Project 1".

# USER STORY

As an employee I want to store my physical location during diﬀerent events so that I can report them to my employer if needed.

## What is a physical location?

Physical location is a piece of information that include coordinates. (latitude and longitude)

It should be possible to attach location to different workshift events.

- start workshift
- end workshift
- start break
- stop break
- start travel
- stop travel

We should append existing tests to support location.

# USER STORY

As an employee 
I want to mark allowances/competences to my workshift 
so that I can report them to my employer. 

## What is allowance?

In Finnish (korvaukset eli kokopäiväraha, osapäiväraha, kilometrikorvaus jne)

Allowance can have following properties:
    - allowance identifier (integer)
    - name or title
    - allowance type (kilometrikorvaus, kotimaan kokopäiväraha, osapäiväraha, ateriakorvaus, muu korvaus)
    - unit (kg, kpl, min, pcs, etc)
    - unit cost (euro)
    - amount
    - timestamp when added
    - description or other simple additional message

Workshift can have only one allowance of each type. Like product, allowance should be copy of given information so it should not change if original allowance details are changed. If we need to update allowance then system should replace old entry.

If you want use real values and what are the unit costs, check Tax Administration page https://www.vero.fi/en/individuals/vehicles/kilometre_and_per_diem_allowances/

### Scenario 1

Given valid workshift
And submit "Kilometrikorvaus", 60km (amount)
Then workshift allowance count is 1
and workshift allowance equals given allowance.

`Repeate scenario 1 for each allowance type`
`You could also test *total costs* so when allowance is added, it should be possible to calculate total costs for allowance (unit cost x amount)`





