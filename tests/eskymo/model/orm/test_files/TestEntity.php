<?php
/** @Id(translate=translated_id) */
class TestEntity extends AEntity
{
	/**
	 * @Translate(translated_name)
	 * @Super(translate=super_name)
	 * @Type(name=enum,values=petr:franta)
	 */
	protected $name;

}