todo: test invalid commentRoot

todo: input data validation + comments + phpdoc - DTOGenerator
todo: input data validation + comments + phpdoc - DTOConverterGenerator

todo: input data validation - DTOGenerator          [test]
todo: input data validation - DTOConverterGenerator [test]

todo: test for: $commentDTOs = $this->utils->convertList($inputData, TopicDTOAssoc::COMMENTS, $this->commentDTOConverter);

todo: AggregateException is too generic, maybe add DTOConversionAggregateException and it can extend generic AggregateException?

todo: refactor obtaining $isConverterConvert; $isList; $isNullable to own method.

todo: split Utils by where are the methods used, e.g. GeneratorUtils, StringUtils, etc.

todo: support nullable for array property in DTO, e.g. 3rd party rest-api can return comments: null so you want property to be array|null.

